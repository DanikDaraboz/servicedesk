<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('PDF Editor') }} - {{ $document->original_filename }}
            </h2>
            <a href="{{ route('pdf.history') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Back to History') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Editor Controls -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <!-- Page Navigation -->
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-700">Страница:</span>
                            <div class="flex items-center space-x-2">
                                <button id="prevPage" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <span id="currentPage" class="font-semibold">1</span>
                                <span class="text-gray-500">/</span>
                                <span id="totalPages" class="text-gray-500">{{ $pageCount }}</span>
                                <button id="nextPage" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Zoom Controls -->
                        <div class="flex items-center space-x-4">
                            <span class="text-gray-700">Масштаб:</span>
                            <div class="flex items-center space-x-2">
                                <button id="zoomOut" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span id="zoomLevel" class="font-semibold w-16 text-center">100%</span>
                                <button id="zoomIn" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-3">
                            <button id="cancelBtn" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed transition-opacity">
                                Отмена
                            </button>
                            <button id="retryBtn" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed transition-opacity">
                                Повторить
                            </button>
                            <button id="createPdfBtn" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                                Создать PDF
                            </button>
                        </div>
                    </div>

                    <!-- Size and Opacity Controls -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Размер подписи</label>
                            <div class="flex items-center space-x-4">
                                <input type="range" id="sizeSlider" min="10" max="500" value="100" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <span id="sizeValue" class="w-20 text-center font-semibold">100px</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Прозрачность</label>
                            <div class="flex items-center space-x-4">
                                <input type="range" id="opacitySlider" min="10" max="100" value="100" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <span id="opacityValue" class="w-20 text-center font-semibold">100%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PDF Viewer and Editor -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- PDF Viewer -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <div id="pdfViewer" class="border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-100" style="height: 500px;">
                                <div class="h-full overflow-auto relative"> <!-- Добавлен relative -->
                                    <!-- Контейнер для центрирования PDF -->
                                    <div id="pdfContainer" class="inline-block p-4 relative"> <!-- Убрали relative, добавим ниже -->
                                        <!-- PDF canvas -->
                                        <canvas id="pdfCanvas" class="block mx-auto shadow-lg" style="max-width: 100%; z-index: 1;"></canvas>
                                        <!-- Контейнер для Fabric canvas - БЕЗ canvas здесь! -->
                                        <div id="fabricContainer" class="absolute top-0 left-0 w-full h-full" style="z-index: 2;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Signature Preview and Info -->
                <div class="space-y-6">
                    <!-- Signature Preview -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-4">Предпросмотр подписи</h3>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 h-48 flex items-center justify-center">
                                <img id="signaturePreviewImg" src="{{ Storage::url($document->signature_image_path) }}" 
                                     alt="Подпись" class="max-h-full max-w-full object-contain">
                            </div>
                            <div class="mt-4 text-sm text-gray-600">
                                <p>Перетащите подпись на документ</p>
                                <p class="mt-1">Измените размер и прозрачность</p>
                            </div>
                        </div>
                    </div>

                    <!-- Document Info -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-4">Информация о документе</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Имя файла:</span>
                                    <span class="font-medium">{{ $document->original_filename }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Дата загрузки:</span>
                                    <span class="font-medium">{{ $document->created_at->format('d.m.Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">UUID:</span>
                                    <span class="font-mono text-sm">{{ $document->uuid }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Статус:</span>
                                    <span id="statusBadge" class="px-3 py-1 rounded-full text-xs font-semibold 
                                        {{ $document->isSigned() ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ $document->isSigned() ? 'Подписан' : 'Не подписан' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Download Links -->
                    @if($document->isSigned())
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h3 class="font-semibold text-lg mb-4 text-green-800">Документ готов!</h3>
                        <div class="space-y-3">
                            <a href="{{ route('pdf.download', ['uuid' => $document->uuid, 'type' => 'signed']) }}" 
                               class="block w-full text-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                                📥 Скачать подписанный PDF
                            </a>
                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('pdf.download', ['uuid' => $document->uuid, 'type' => 'original']) }}" 
                                   class="text-center px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm">
                                    Оригинал
                                </a>
                                <a href="{{ route('pdf.download', ['uuid' => $document->uuid, 'type' => 'signature']) }}" 
                                   class="text-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                    Подпись
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="text-center">
                <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-4"></div>
                <h3 class="text-xl font-semibold mb-2">Создание PDF</h3>
                <p class="text-gray-600 mb-4">Пожалуйста, подождите...</p>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progressBar" class="bg-blue-600 h-2 rounded-full w-0"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-8 rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Успешно!</h3>
                <p id="successMessage" class="text-gray-600 mb-6">PDF успешно создан</p>
                <div class="space-y-3">
                    <a id="downloadLink" href="#" 
                       class="block w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                        📥 Скачать документ
                    </a>
                    <button onclick="location.reload()" 
                            class="w-full px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300">
                        Вернуться к редактору
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
<style>
    /* Фиксируем размеры области просмотра */
    #pdfViewer {
        background: #f8f9fa !important;
        height: 500px !important;
        overflow: auto !important;
    }
    
    /* Контейнер для PDF - inline-block чтобы центрировать */
    #pdfContainer {
        background: transparent !important;
        position: relative;
        display: inline-block;
        margin: 0 auto;
    }
    
    /* PDF canvas - ограничиваем максимальную ширину */
    #pdfCanvas {
        background: white !important;
        display: block;
        max-width: 100%;
        height: auto;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    /* Fabric canvas - абсолютно поверх PDF */
    #fabricCanvas {
        background: transparent !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        pointer-events: auto !important;
        z-index: 10;
    }
    
    /* Убираем стандартные стили Fabric.js */
    .canvas-container {
        background: transparent !important;
        box-shadow: none !important;
        border: none !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
    }
    
    /* Делаем скролл плавным */
    #pdfViewer::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    #pdfViewer::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    #pdfViewer::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    
    #pdfViewer::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
@endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration
        const config = {
            documentUuid: "{{ $document->uuid }}",
            pdfUrl: "{{ Storage::url($document->original_pdf_path) }}",
            signatureUrl: "{{ Storage::url($document->signature_image_path) }}",
            csrfToken: "{{ csrf_token() }}"
        };

        // State
        let state = {
            currentPage: 1,
            totalPages: {{ $pageCount }},
            scale: 1.0,
            pdfDoc: null,
            fabricCanvas: null,
            signatureObj: null,
            isDragging: false,
            // История для Undo/Redo
            history: [],
            historyIndex: -1,
            maxHistorySize: 50,
            isApplyingState: false // Флаг для предотвращения сохранения при применении состояния из истории
        };

        // Initialize
        initPDFViewer();
        initFabricCanvas();
        initControls();

        // PDF.js Initialization
        async function initPDFViewer() {
            try {
                pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                
                state.pdfDoc = await pdfjsLib.getDocument(config.pdfUrl).promise;
                state.totalPages = state.pdfDoc.numPages;
                document.getElementById('totalPages').textContent = state.totalPages;
                
                await renderPage(state.currentPage);
            } catch (error) {
                console.error('Error loading PDF:', error);
                alert('Ошибка загрузки PDF файла');
            }
        }

        // Fabric.js Initialization
function initFabricCanvas() {
    // Используем КОНТЕЙНЕР для Fabric, а не canvas
    const container = document.getElementById('fabricContainer');
    
    // Создаём canvas для Fabric вручную
    const fabricCanvasEl = document.createElement('canvas');
    fabricCanvasEl.id = 'fabricCanvas';
    container.appendChild(fabricCanvasEl);
    
    // Инициализируем Fabric на этом canvas
    state.fabricCanvas = new fabric.Canvas(fabricCanvasEl, {
        selection: false,
        preserveObjectStacking: true,
        backgroundColor: 'transparent',
        renderOnAddRemove: false
    });
    
    // Убираем стандартные стили Fabric.js
    state.fabricCanvas.upperCanvasEl.style.pointerEvents = 'auto';
    state.fabricCanvas.lowerCanvasEl.style.backgroundColor = 'transparent';
    
    // Загружаем подпись
    fabric.Image.fromURL(config.signatureUrl, function(img) {
        const originalWidth = img.width;
        const originalHeight = img.height;
        
        const targetWidth = {{ $document->signature_width }};
        const targetHeight = {{ $document->signature_height }};
        
        const scaleX = targetWidth / originalWidth;
        const scaleY = targetHeight / originalHeight;
        
        state.signatureObj = img;
        state.signatureObj.set({
            left: {{ $document->position_x }},
            top: {{ $document->position_y }},
            scaleX: scaleX,
            scaleY: scaleY,
            opacity: {{ $document->opacity }},
            selectable: true,
            hasControls: true,
            hasBorders: true,
            lockRotation: true,
            lockScalingFlip: true,
            lockUniScaling: false,
            originX: 'left',
            originY: 'top'
        });
        
        state.fabricCanvas.add(state.signatureObj);
        state.fabricCanvas.setActiveObject(state.signatureObj);
        state.fabricCanvas.renderAll();
        
        updateSizeAndOpacityDisplays();
        
        // После загрузки подписи обновляем размеры
        setTimeout(() => {
            resizeCanvasToPDF();
        }, 100);
    }, {
        crossOrigin: 'anonymous'
    });
    
    // Сохраняем начальное состояние в историю
    setTimeout(() => {
        if (state.signatureObj) {
            saveStateToHistory();
        }
    }, 200);
    
    // Обновляем контролы и отслеживаем изменения для истории
    state.fabricCanvas.on('object:modified', function(e) {
        updateSizeAndOpacityDisplays();
        // Сохраняем состояние после изменения с небольшой задержкой
        clearTimeout(state.historyTimeout);
        state.historyTimeout = setTimeout(() => {
            saveStateToHistory();
        }, 300);
    });
    
    // Отслеживаем перемещение объекта
    state.fabricCanvas.on('object:moving', function(e) {
        // Не сохраняем во время перемещения, только после завершения
    });
    
    state.fabricCanvas.on('object:moved', function(e) {
        clearTimeout(state.historyTimeout);
        state.historyTimeout = setTimeout(() => {
            saveStateToHistory();
        }, 300);
    });
}

// Render PDF Page
async function renderPage(pageNum) {
    try {
        const page = await state.pdfDoc.getPage(pageNum);
        const canvas = document.getElementById('pdfCanvas');
        const ctx = canvas.getContext('2d');
        
        const viewport = page.getViewport({ scale: state.scale });
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        
        // ВАЖНО: Очищаем canvas ПРОЗРАЧНЫМ цветом, а не белым
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Создаем временный canvas для рендеринга PDF
        const tempCanvas = document.createElement('canvas');
        const tempCtx = tempCanvas.getContext('2d');
        tempCanvas.width = viewport.width;
        tempCanvas.height = viewport.height;
        
        // Рендерим PDF на временный canvas
        await page.render({
            canvasContext: tempCtx,
            viewport: viewport
        }).promise;
        
        // Копируем ТОЛЬКО содержимое PDF на основной canvas
        // Это убирает белый фон
        ctx.drawImage(tempCanvas, 0, 0);
        
        document.getElementById('currentPage').textContent = pageNum;
        resizeCanvasToPDF();
    } catch (error) {
        console.error('Error rendering page:', error);
    }
}

// Resize Fabric canvas to match PDF canvas
function resizeCanvasToPDF() {
    const pdfCanvas = document.getElementById('pdfCanvas');
    const fabricCanvas = state.fabricCanvas;
    const pdfContainer = document.getElementById('pdfContainer');
    const fabricContainer = document.getElementById('fabricContainer');
    
    if (pdfCanvas && fabricCanvas && pdfContainer && fabricContainer) {
        // Размеры PDF canvas
        const pdfWidth = pdfCanvas.width;
        const pdfHeight = pdfCanvas.height;
        
        // Устанавливаем размеры контейнера Fabric
        fabricContainer.style.width = pdfWidth + 'px';
        fabricContainer.style.height = pdfHeight + 'px';
        
        // Устанавливаем размеры самого canvas Fabric
        fabricCanvas.setWidth(pdfWidth);
        fabricCanvas.setHeight(pdfHeight);
        
        // Обновляем отступы
        fabricCanvas.calcOffset();
        
        // Обновляем координаты подписи
        if (state.signatureObj) {
            state.signatureObj.setCoords();
        }
        
        fabricCanvas.renderAll();
        
        // Дебаг информация
        console.log('Canvas resized:', {
            pdf: { width: pdfWidth, height: pdfHeight },
            fabric: { width: fabricCanvas.width, height: fabricCanvas.height },
            container: fabricContainer.getBoundingClientRect()
        });
    }
}

        // Initialize UI Controls
        function initControls() {
            // Page navigation
            document.getElementById('prevPage').addEventListener('click', () => {
                if (state.currentPage > 1) {
                    state.currentPage--;
                    renderPage(state.currentPage);
                }
            });
            
            document.getElementById('nextPage').addEventListener('click', () => {
                if (state.currentPage < state.totalPages) {
                    state.currentPage++;
                    renderPage(state.currentPage);
                }
            });
            
            // Zoom controls
            document.getElementById('zoomIn').addEventListener('click', () => {
                state.scale = Math.min(state.scale + 0.25, 3);
                updateZoomDisplay();
                renderPage(state.currentPage);
            });
            
            document.getElementById('zoomOut').addEventListener('click', () => {
                state.scale = Math.max(state.scale - 0.25, 0.5);
                updateZoomDisplay();
                renderPage(state.currentPage);
            });
            
            // Size slider
            const sizeSlider = document.getElementById('sizeSlider');
sizeSlider.value = {{ $document->signature_width }};
let sizeSliderTimeout;
sizeSlider.addEventListener('input', function() {
    if (state.signatureObj) {
        const newWidth = parseInt(this.value);
        const originalWidth = state.signatureObj.width;
        const scaleX = newWidth / originalWidth;
        
        // Сохраняем пропорции
        const originalHeight = state.signatureObj.height;
        const currentHeight = state.signatureObj.getScaledHeight();
        const scaleY = scaleX; // Сохраняем одинаковый масштаб для пропорций
        
        state.signatureObj.set({
            scaleX: scaleX,
            scaleY: scaleY
        });
        
        state.signatureObj.setCoords();
        state.fabricCanvas.renderAll();
        updateSizeAndOpacityDisplays();
        
        // Сохраняем в историю после завершения изменения
        clearTimeout(sizeSliderTimeout);
        sizeSliderTimeout = setTimeout(() => {
            saveStateToHistory();
        }, 500);
    }
});

// Opacity slider
const opacitySlider = document.getElementById('opacitySlider');
opacitySlider.value = {{ $document->opacity * 100 }};
let opacitySliderTimeout;
opacitySlider.addEventListener('input', function() {
    if (state.signatureObj) {
        const opacity = parseInt(this.value) / 100;
        state.signatureObj.set({ opacity: opacity });
        state.fabricCanvas.renderAll();
        updateSizeAndOpacityDisplays();
        
        // Сохраняем в историю после завершения изменения
        clearTimeout(opacitySliderTimeout);
        opacitySliderTimeout = setTimeout(() => {
            saveStateToHistory();
        }, 500);
    }
});
            
            // Cancel button (Отмена) - отменяет последнее действие
            document.getElementById('cancelBtn').addEventListener('click', function() {
                undo();
            });
            
            // Retry button (Повторить) - возвращает отмененное действие
            document.getElementById('retryBtn').addEventListener('click', function() {
                redo();
            });
            
            // Create PDF button
            document.getElementById('createPdfBtn').addEventListener('click', createSignedPdf);
        }

        // История состояний (Undo/Redo)
        function saveStateToHistory() {
            if (!state.signatureObj || state.isApplyingState) return;
            
            const currentState = {
                left: state.signatureObj.left,
                top: state.signatureObj.top,
                scaleX: state.signatureObj.scaleX,
                scaleY: state.signatureObj.scaleY,
                opacity: state.signatureObj.opacity
            };
            
            // Удаляем все состояния после текущего индекса (если делаем новое действие после отмены)
            if (state.historyIndex < state.history.length - 1) {
                state.history = state.history.slice(0, state.historyIndex + 1);
            }
            
            // Проверяем, отличается ли новое состояние от последнего
            const lastState = state.history[state.history.length - 1];
            if (lastState && 
                lastState.left === currentState.left &&
                lastState.top === currentState.top &&
                lastState.scaleX === currentState.scaleX &&
                lastState.scaleY === currentState.scaleY &&
                lastState.opacity === currentState.opacity) {
                return; // Состояние не изменилось, не сохраняем
            }
            
            // Добавляем новое состояние
            state.history.push(currentState);
            state.historyIndex = state.history.length - 1;
            
            // Ограничиваем размер истории
            if (state.history.length > state.maxHistorySize) {
                state.history.shift();
                state.historyIndex--;
            }
            
            updateUndoRedoButtons();
        }
        
        function undo() {
            if (state.historyIndex <= 0 || !state.signatureObj) {
                showNotification('Нет действий для отмены', 'info');
                return;
            }
            
            state.historyIndex--;
            const previousState = state.history[state.historyIndex];
            applyState(previousState);
            updateUndoRedoButtons();
        }
        
        function redo() {
            if (state.historyIndex >= state.history.length - 1 || !state.signatureObj) {
                showNotification('Нет действий для повтора', 'info');
                return;
            }
            
            state.historyIndex++;
            const nextState = state.history[state.historyIndex];
            applyState(nextState);
            updateUndoRedoButtons();
        }
        
        function applyState(stateToApply) {
            if (!state.signatureObj || !stateToApply) return;
            
            // Устанавливаем флаг, чтобы не сохранять это состояние в историю
            state.isApplyingState = true;
            
            state.signatureObj.set({
                left: stateToApply.left,
                top: stateToApply.top,
                scaleX: stateToApply.scaleX,
                scaleY: stateToApply.scaleY,
                opacity: stateToApply.opacity
            });
            
            state.signatureObj.setCoords();
            state.fabricCanvas.renderAll();
            
            // Обновляем слайдеры
            const sizeSlider = document.getElementById('sizeSlider');
            const opacitySlider = document.getElementById('opacitySlider');
            
            if (sizeSlider) {
                const newWidth = Math.round(state.signatureObj.getScaledWidth());
                sizeSlider.value = newWidth;
            }
            
            if (opacitySlider) {
                opacitySlider.value = Math.round(state.signatureObj.opacity * 100);
            }
            
            updateSizeAndOpacityDisplays();
            
            // Снимаем флаг после небольшой задержки
            setTimeout(() => {
                state.isApplyingState = false;
            }, 100);
        }
        
        function updateUndoRedoButtons() {
            const cancelBtn = document.getElementById('cancelBtn');
            const retryBtn = document.getElementById('retryBtn');
            
            // Отмена доступна, если есть действия для отмены
            if (cancelBtn) {
                cancelBtn.disabled = state.historyIndex <= 0;
                cancelBtn.classList.toggle('opacity-50', state.historyIndex <= 0);
                cancelBtn.classList.toggle('cursor-not-allowed', state.historyIndex <= 0);
            }
            
            // Повторить доступен, если есть отмененные действия
            if (retryBtn) {
                retryBtn.disabled = state.historyIndex >= state.history.length - 1;
                retryBtn.classList.toggle('opacity-50', state.historyIndex >= state.history.length - 1);
                retryBtn.classList.toggle('cursor-not-allowed', state.historyIndex >= state.history.length - 1);
            }
        }
        
        // Update display functions
        function updateZoomDisplay() {
            document.getElementById('zoomLevel').textContent = Math.round(state.scale * 100) + '%';
        }
        
       function updateSizeAndOpacityDisplays() {
    if (state.signatureObj) {
        // Получаем реальные размеры с учетом масштаба
        const width = Math.round(state.signatureObj.getScaledWidth());
        const height = Math.round(state.signatureObj.getScaledHeight());
        const opacity = Math.round(state.signatureObj.opacity * 100);
        
        document.getElementById('sizeValue').textContent = width + 'px';
        document.getElementById('opacityValue').textContent = opacity + '%';
        
        // Обновляем слайдеры
        document.getElementById('sizeSlider').value = width;
        
        // Для слайдера ширины - ограничиваем значения
        const sizeSlider = document.getElementById('sizeSlider');
        sizeSlider.min = 10;
        sizeSlider.max = Math.min(500, state.fabricCanvas.width * 0.5); // Не больше половины ширины canvas
        
        document.getElementById('opacitySlider').value = opacity;
    }
}
        
        function updateCoordinates() {
            if (state.signatureObj) {
                // Update coordinates display if needed
            }
        }
        // Create signed PDF
       async function createSignedPdf() {
    if (!state.signatureObj) return;

    const loadingModal = document.getElementById('loadingModal');
    loadingModal.classList.remove('hidden');

    try {
        const signature = state.signatureObj;
        const pdfCanvas = document.getElementById('pdfCanvas');
        
        // ✅ ШАГ 1: Получаем информацию о странице PDF
        const page = await state.pdfDoc.getPage(state.currentPage);
        const viewport = page.getViewport({ scale: state.scale });
        
        // ✅ ШАГ 2: Получаем реальные размеры PDF в точках (points)
        const pdfWidthPoints = page.view[2];
        const pdfHeightPoints = page.view[3];
        
        // ✅ ШАГ 3: ТОЧНЫЕ коэффициенты конвертации
        const scaleX = pdfWidthPoints / viewport.width;
        const scaleY = pdfHeightPoints / viewport.height;
        
        // ✅ ШАГ 4: Конвертируем координаты с правильными масштабами
        // FPDF/FPDI используют mm, но мы будем работать в точках для точности
        
        // X координата - масштабируем и добавляем небольшую корректировку вправо (+2 точки)
        const pdfX = signature.left * scaleX - 18;
        
        // Y координата - canvas и PDF оба считают от верха, корректируем вверх (-2 точки)
        const pdfY = signature.top * scaleY - 10;
        
        // Ширина/высота - используем scaleY для обоих размеров для правильного размера
        // scaleY обычно немного больше scaleX (≈1.009), что компенсирует разницу в размерах
        // Это обеспечивает, что подпись будет правильного размера в PDF
        const pdfWidth = signature.getScaledWidth() * scaleY;
        const pdfHeight = signature.getScaledHeight() * scaleY;
        
        console.log('Точные координаты для PDF:', {
            // Canvas координаты (пиксели)
            canvas: {
                x: signature.left,
                y: signature.top,
                width: signature.getScaledWidth(),
                height: signature.getScaledHeight()
            },
            // PDF координаты (точки) БЕЗ округления
            pdf_exact: {
                x: pdfX,
                y: pdfY,
                width: pdfWidth,
                height: pdfHeight
            },
            // Масштабы
            scales: { scaleX, scaleY },
            // Размеры PDF
            pdfPoints: { width: pdfWidthPoints, height: pdfHeightPoints },
            viewport: { width: viewport.width, height: viewport.height }
        });
        
        // ✅ Отправляем координаты с большей точностью (4 знака)
        const response = await fetch(`/pdf/sign/${config.documentUuid}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': config.csrfToken
            },
            body: JSON.stringify({
                page: state.currentPage,
                x: parseFloat(pdfX.toFixed(4)),    // 4 знака после запятой
                y: parseFloat(pdfY.toFixed(4)),
                width: parseFloat(pdfWidth.toFixed(4)),
                height: parseFloat(pdfHeight.toFixed(4)),
                opacity: parseFloat(signature.opacity.toFixed(2))
            })
        });

        // Проверяем ответ
        if (!response.ok) {
            const text = await response.text();
            console.error('Server error:', text);
            throw new Error(`HTTP ${response.status}: ${text.substring(0, 200)}`);
        }

        const result = await response.json();
        loadingModal.classList.add('hidden');

        if (result.success) {
            showSuccessModal(result);
        } else {
            showNotification('Ошибка создания PDF: ' + (result.message || 'Неизвестная ошибка'), 'error');
        }

    } catch (e) {
        loadingModal.classList.add('hidden');
        console.error('Error:', e);
        showNotification('Ошибка соединения: ' + e.message, 'error');
    }
}


async function debugSignaturePosition() {
    if (!state.signatureObj) {
        console.error('Подпись не загружена');
        return;
    }
    
    const signature = state.signatureObj;
    const page = await state.pdfDoc.getPage(state.currentPage);
    const viewport = page.getViewport({ scale: state.scale });
    
    console.log('=== ОТЛАДКА КООРДИНАТ ===');
    console.log('1. В редакторе (пиксели):');
    console.log('   Позиция: X=' + signature.left + ' Y=' + signature.top);
    console.log('   Размеры: ' + signature.getScaledWidth() + 'x' + signature.getScaledHeight());
    console.log('   Масштаб: ' + state.scale + 'x');
    
    console.log('\n2. PDF информация:');
    console.log('   Ширина PDF: ' + page.view[2] + ' точек');
    console.log('   Высота PDF: ' + page.view[3] + ' точек');
    console.log('   Ширина canvas: ' + viewport.width + 'px');
    console.log('   Высота canvas: ' + viewport.height + 'px');
    
    console.log('\n3. Конвертация:');
    const scaleX = page.view[2] / viewport.width;
    const scaleY = page.view[3] / viewport.height;
    console.log('   scaleX: ' + scaleX);
    console.log('   scaleY: ' + scaleY);
    console.log('   PDF X: ' + (signature.left * scaleX) + ' точек');
    console.log('   PDF Y: ' + (signature.top * scaleY) + ' точек');
    console.log('   PDF X (мм): ' + (signature.left * scaleX * 0.352777778) + ' мм');
    console.log('   PDF Y (мм): ' + (signature.top * scaleY * 0.352777778) + ' мм');
}

        // Show success modal
        function showSuccessModal(result) {
    const successModal = document.getElementById('successModal');
    const downloadLink = document.getElementById('downloadLink');
    const successMessage = document.getElementById('successMessage');
    
    successMessage.textContent = result.message || 'PDF успешно создан!';
    downloadLink.href = result.download_url || '#';
    
    // Обновляем статус БЕЗ перезагрузки страницы
    const statusBadge = document.getElementById('statusBadge');
    statusBadge.textContent = 'Подписан';
    statusBadge.className = 'px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800';
    
    // Показываем блок с ссылками для скачивания
    const downloadLinksSection = document.querySelector('.bg-green-50');
    if (downloadLinksSection) {
        downloadLinksSection.classList.remove('hidden');
    }
    
    successModal.classList.remove('hidden');
}

        // Utility functions
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
                type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
                'bg-blue-100 text-blue-800 border border-blue-200'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    ${type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'}
                    <span class="ml-2 font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        
        // Initial displays
        updateZoomDisplay();
        updateSizeAndOpacityDisplays();
        
        // Инициализируем состояние кнопок Undo/Redo
        setTimeout(() => {
            updateUndoRedoButtons();
        }, 300);
        
        // Горячие клавиши для Undo/Redo
        document.addEventListener('keydown', function(e) {
            // Ctrl+Z или Cmd+Z для отмены
            if ((e.ctrlKey || e.metaKey) && e.key === 'z' && !e.shiftKey) {
                e.preventDefault();
                undo();
            }
            // Ctrl+Y или Ctrl+Shift+Z или Cmd+Shift+Z для повтора
            if ((e.ctrlKey || e.metaKey) && (e.key === 'y' || (e.key === 'z' && e.shiftKey))) {
                e.preventDefault();
                redo();
            }
        });
    });
    </script>
    @endpush
</x-app-layout>