<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use setasign\Fpdi\Fpdi;

class DocumentController extends Controller
{
    /**
     * Показать форму загрузки
     */
    public function index()
    {
        return view('pdf.index');
    }

    /**
     * Загрузить PDF и подпись
     */
    public function upload(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240', // 10MB
            'signature' => 'required|file|mimes:jpg,jpeg,png|max:2048', // 2MB
        ]);

        // Генерируем UUID
        $uuid = Str::uuid();

        // Сохраняем PDF
        $pdfPath = $request->file('pdf')->storeAs(
            'documents/originals',
            $uuid . '.pdf',
            'public'
        );

        $signaturePath = $request->file('signature')->storeAs(
            'documents/signatures',
            $uuid . '.' . $request->file('signature')->getClientOriginalExtension(),
            'public'
        );

        // Создаем запись в БД
        $document = Document::create([
            'uuid' => $uuid,
            'original_filename' => $request->file('pdf')->getClientOriginalName(),
            'original_pdf_path' => $pdfPath,
            'signature_image_path' => $signaturePath,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('pdf.editor', $document->uuid);
    }

    /**
     * Редактор размещения подписи
     */
    public function editor(string $uuid)
    {
        $document = Document::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Получаем количество страниц PDF
        $pageCount = $this->getPdfPageCount(storage_path('app/public/' . $document->original_pdf_path));

        return view('pdf.editor', compact('document', 'pageCount'));
    }

    /**
     * Сохранить подписанный PDF
     */
    public function sign(Request $request, string $uuid)
    {
        $request->validate([
            'page' => 'required|integer|min:1',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'width' => 'required|numeric|min:10|max:500',
            'height' => 'required|numeric|min:10|max:500',
            'opacity' => 'required|numeric|min:0.1|max:1',
        ]);

        $document = Document::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Обновляем позицию
        $document->update([
            'page_number' => $request->page,
            'position_x' => $request->x,
            'position_y' => $request->y,
            'signature_width' => $request->width,
            'signature_height' => $request->height,
            'opacity' => $request->opacity,
        ]);

        // Создаем подписанный PDF
        $signedPath = $this->createSignedPdf($document);

        $document->update([
            'signed_pdf_path' => $signedPath,
        ]);

        return response()->json([
            'success' => true,
            'download_url' => $document->getDownloadUrl(),
            'message' => 'PDF успешно подписан!'
        ]);
    }

    /**
     * История документов
     */
    public function history()
    {
        $documents = Document::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pdf.history', compact('documents'));
    }

    /**
     * Скачать файл
     */
    public function download(string $uuid, string $type)
{
    $document = Document::where('uuid', $uuid)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    // Определяем путь к файлу
    $path = match($type) {
        'original' => $document->original_pdf_path,
        'signature' => $document->signature_image_path,
        'signed' => $document->signed_pdf_path,
        default => $document->signed_pdf_path,
    };

    // Для отладки
    \Log::info('Download request', [
        'uuid' => $uuid,
        'type' => $type,
        'path' => $path,
        'storage_exists' => Storage::exists($path),
        'file_exists' => file_exists(storage_path('app/public/' . $path))
    ]);

    if (!$path) {
        abort(404, 'Файл не найден (путь пустой)');
    }

    // Проверяем существование файла
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404, 'Файл не найден по пути: ' . $fullPath);
    }

    // Определяем имя файла для скачивания
    $filename = match($type) {
        'original' => $document->original_filename,
        'signature' => 'signature.' . pathinfo($path, PATHINFO_EXTENSION),
        'signed' => 'signed_' . $document->original_filename,
    };

    // Используем response()->file() для отдачи файла
    return response()->file($fullPath, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ]);
}

    /**
     * Удалить документ
     */
    public function destroy(string $uuid)
    {
        $document = Document::where('uuid', $uuid)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Удаляем файлы
        Storage::delete([
            $document->original_pdf_path,
            $document->signature_image_path,
            $document->signed_pdf_path,
        ]);

        $document->delete();

        return redirect()->route('pdf.history')
            ->with('success', 'Документ успешно удален');
    }

    /**
     * Получить количество страниц PDF
     */
    private function getPdfPageCount(string $pdfPath): int
    {
        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($pdfPath);
            return $pageCount;
        } catch (\Exception $e) {
            return 1;
        }
    }

    /**
     * Создать подписанный PDF
     */
   private function createSignedPdf(Document $document): string
{
    $originalPath = storage_path('app/public/' . $document->original_pdf_path);
    $signaturePath = storage_path('app/public/' . $document->signature_image_path);

    // Логирование для отладки
    \Log::info('=== PDF CREATION START ===');
    \Log::info('Document:', [
        'id' => $document->id,
        'uuid' => $document->uuid,
        'coords' => [
            'x' => $document->position_x,
            'y' => $document->position_y,
            'width' => $document->signature_width,
            'height' => $document->signature_height,
            'opacity' => $document->opacity
        ]
    ]);

    $pdf = new Fpdi();
    
    // Получаем количество страниц
    $pageCount = $pdf->setSourceFile($originalPath);
    
    // Обрабатываем каждую страницу
    for ($page = 1; $page <= $pageCount; $page++) {
        $templateId = $pdf->importPage($page);
        $size = $pdf->getTemplateSize($templateId);
        
        // Определяем размеры страницы в мм
        $pageWidth = is_array($size) ? ($size['width'] ?? $size[0]) : 210;
        $pageHeight = is_array($size) ? ($size['height'] ?? $size[1]) : 297;
        
        $pdf->AddPage('P', [$pageWidth, $pageHeight]);
        $pdf->useTemplate($templateId);
        
        // Добавляем подпись на нужную страницу
        if ($page == $document->page_number) {
            // ✅ Координаты приходят в PDF точках (points)
            // 1 точка = 1/72 дюйма = 25.4/72 мм ≈ 0.352777778 мм
            $pointToMm = 25.4 / 72; // ≈ 0.352777778
            
            // Конвертируем точки в мм
            $x_mm = $document->position_x * $pointToMm;
            $y_mm = $document->position_y * $pointToMm;
            $width_mm = $document->signature_width * $pointToMm;
            $height_mm = $document->signature_height * $pointToMm;
            
            \Log::info('Adding signature (points → mm):', [
                'points' => [
                    'x' => $document->position_x,
                    'y' => $document->position_y,
                    'width' => $document->signature_width,
                    'height' => $document->signature_height
                ],
                'mm' => [
                    'x' => $x_mm,
                    'y' => $y_mm,
                    'width' => $width_mm,
                    'height' => $height_mm
                ],
                'page_size_mm' => [
                    'width' => $pageWidth,
                    'height' => $pageHeight
                ],
                'opacity' => $document->opacity
            ]);
            
            // Проверяем границы (без автоматической корректировки)
            if ($x_mm + $width_mm > $pageWidth) {
                \Log::warning('Signature would exceed page width');
            }
            
            if ($y_mm + $height_mm > $pageHeight) {
                \Log::warning('Signature would exceed page height');
            }
            
            // ✅ Простая обработка прозрачности БЕЗ Intervention Image
            $finalSignaturePath = $this->processSignatureImage($signaturePath, $document->opacity);
            
            try {
                $pdf->Image(
                    $finalSignaturePath, 
                    $x_mm, 
                    $y_mm, 
                    $width_mm, 
                    $height_mm
                );
                
                \Log::info('✓ Signature added successfully');
                
                // Удаляем временный файл, если был создан
                if ($finalSignaturePath !== $signaturePath && file_exists($finalSignaturePath)) {
                    @unlink($finalSignaturePath);
                }
                
            } catch (\Exception $e) {
                \Log::error('Failed to add signature: ' . $e->getMessage());
                
                // Удаляем временный файл в случае ошибки
                if ($finalSignaturePath !== $signaturePath && file_exists($finalSignaturePath)) {
                    @unlink($finalSignaturePath);
                }
                
                throw $e;
            }
        }
    }
    
    // Сохраняем результат
    $signedPath = 'documents/signed/' . $document->uuid . '.pdf';
    $fullSignedPath = storage_path('app/public/' . $signedPath);
    
    $signedDir = dirname($fullSignedPath);
    if (!is_dir($signedDir)) {
        mkdir($signedDir, 0755, true);
    }
    
    $pdf->Output($fullSignedPath, 'F');
    
    \Log::info('PDF saved to: ' . $fullSignedPath);
    \Log::info('File size: ' . filesize($fullSignedPath) . ' bytes');
    \Log::info('=== PDF CREATION END ===');
    
    return $signedPath;
}

/**
 * Обработать изображение подписи с учетом прозрачности
 */
private function processSignatureImage(string $signaturePath, float $opacity): string
{
    // Если прозрачность 100%, возвращаем оригинальный путь
    if ($opacity >= 1.0) {
        return $signaturePath;
    }
    
    try {
        // Используем GD для обработки прозрачности
        $extension = strtolower(pathinfo($signaturePath, PATHINFO_EXTENSION));
        
        // Загружаем изображение в зависимости от формата
        switch ($extension) {
            case 'png':
                $image = imagecreatefrompng($signaturePath);
                break;
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($signaturePath);
                break;
            default:
                throw new \Exception("Unsupported image format: {$extension}");
        }
        
        if (!$image) {
            throw new \Exception("Failed to load image: {$signaturePath}");
        }
        
        // Получаем размеры
        $width = imagesx($image);
        $height = imagesy($image);
        
        // Создаем новое изображение с прозрачностью
        $resultImage = imagecreatetruecolor($width, $height);
        
        // Включаем альфа-канал для PNG
        imagesavealpha($resultImage, true);
        $transparent = imagecolorallocatealpha($resultImage, 0, 0, 0, 127);
        imagefill($resultImage, 0, 0, $transparent);
        
        // Применяем прозрачность
        $opacityLevel = (int)(127 * (1 - $opacity)); // 0 = полностью непрозрачный, 127 = полностью прозрачный
        
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                $color = imagecolorat($image, $x, $y);
                $rgba = imagecolorsforindex($image, $color);
                
                // Создаем цвет с новой прозрачностью
                $newColor = imagecolorallocatealpha(
                    $resultImage,
                    $rgba['red'],
                    $rgba['green'],
                    $rgba['blue'],
                    min(127, $rgba['alpha'] + $opacityLevel)
                );
                
                imagesetpixel($resultImage, $x, $y, $newColor);
            }
        }
        
        // Сохраняем во временный файл
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        $tempPath = $tempDir . '/signature_' . uniqid() . '.png';
        imagepng($resultImage, $tempPath);
        
        // Освобождаем память
        imagedestroy($image);
        imagedestroy($resultImage);
        
        return $tempPath;
        
    } catch (\Exception $e) {
        \Log::error('Error processing signature image: ' . $e->getMessage());
        
        // В случае ошибки возвращаем оригинальный путь
        return $signaturePath;
    }
}
}