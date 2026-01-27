<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'original_filename',
        'original_pdf_path',
        'signature_image_path',
        'signed_pdf_path',
        'page_number',
        'position_x',
        'position_y',
        'signature_width',
        'signature_height',
        'opacity',
        'user_id',
    ];

    protected $casts = [
        'position_x' => 'decimal:2',
        'position_y' => 'decimal:2',
        'signature_width' => 'decimal:2',
        'signature_height' => 'decimal:2',
        'opacity' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить URL для скачивания файла
     */
    public function getDownloadUrl(string $type = 'signed'): string
    {
        return route('pdf.download', ['uuid' => $this->uuid, 'type' => $type]);
    }

    /**
     * Проверить, подписан ли документ
     */
    public function isSigned(): bool
    {
        if (empty($this->signed_pdf_path)) {
            return false;
        }
        
        $path = storage_path('app/public/' . $this->signed_pdf_path);
        return file_exists($path);
    }
}