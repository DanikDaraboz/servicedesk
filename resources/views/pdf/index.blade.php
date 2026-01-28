<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Подписание PDF документов
            </h2>
            <div class="space-x-2">
                <a href="{{ route('pdf.history') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    История
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-center mb-2">Загрузка документа для подписи</h3>
                    <p class="text-gray-600 text-center mb-8">Загрузите PDF документ и изображение подписи</p>
                    
                    <form id="uploadForm" action="{{ route('pdf.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <!-- PDF Upload with Drag & Drop -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors relative" 
                             id="pdfDropZone">
                            <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold mb-2">📄 Загрузите PDF документ</h4>
                            <p class="text-gray-500 mb-4">Перетащите PDF файл сюда или кликните для выбора</p>
                            <p class="text-sm text-gray-400 mb-4">Максимальный размер: 10 МБ</p>
                            <input type="file" name="pdf" id="pdfInput" accept=".pdf" class="hidden" required>
                            <label for="pdfInput" class="cursor-pointer inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Выбрать файл
                            </label>
                            <div id="pdfPreview" class="mt-4 text-sm text-gray-600 hidden">
                                <div class="flex items-center justify-center bg-blue-50 rounded-lg p-3">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span id="pdfFileName" class="font-medium"></span>
                                    <span class="mx-2">•</span>
                                    <span id="pdfFileSize" class="text-gray-500"></span>
                                    <button type="button" onclick="removePdfFile()" class="ml-2 text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <!-- Drag & Drop Overlay -->
                            <div id="pdfDragOverlay" class="absolute inset-0 bg-blue-50 bg-opacity-90 border-4 border-dashed border-blue-300 rounded-lg flex items-center justify-center hidden z-10">
                                <div class="text-center p-4">
                                    <svg class="w-12 h-12 text-blue-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-blue-700 font-medium">Отпустите файл для загрузки</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Signature Upload with Drag & Drop -->
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-green-400 transition-colors relative" 
                             id="signatureDropZone">
                            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold mb-2">✍️ Загрузите подпись</h4>
                            <p class="text-gray-500 mb-4">Перетащите изображение сюда или кликните для выбора</p>
                            <p class="text-sm text-gray-400 mb-4">PNG или JPG, макс. 2 МБ</p>
                            <input type="file" name="signature" id="signatureInput" accept=".jpg,.jpeg,.png" class="hidden" required>
                            <label for="signatureInput" class="cursor-pointer inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Выбрать изображение
                            </label>
                            <div id="signaturePreview" class="mt-4 hidden">
                                <div class="bg-green-50 rounded-lg p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="font-medium">Изображение подписи</span>
                                        </div>
                                        <button type="button" onclick="removeSignatureFile()" class="text-red-500 hover:text-red-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <img id="signatureImage" class="mx-auto h-32 object-contain border rounded" alt="Предпросмотр подписи">
                                </div>
                            </div>
                            <!-- Drag & Drop Overlay -->
                            <div id="signatureDragOverlay" class="absolute inset-0 bg-green-50 bg-opacity-90 border-4 border-dashed border-green-300 rounded-lg flex items-center justify-center hidden z-10">
                                <div class="text-center p-4">
                                    <svg class="w-12 h-12 text-green-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-green-700 font-medium">Отпустите изображение для загрузки</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center pt-8">
                            <button type="submit" id="submitBtn" disabled
                                    class="inline-flex items-center px-8 py-4 bg-indigo-600 border border-transparent rounded-lg font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed">
                                Далее
                                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Instructions -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 p-6 rounded-lg">
                    <div class="text-blue-600 font-bold mb-2">1. Загрузите файлы</div>
                    <p class="text-sm text-blue-800">Перетащите PDF и изображение подписи или выберите файлы</p>
                </div>
                <div class="bg-green-50 p-6 rounded-lg">
                    <div class="text-green-600 font-bold mb-2">2. Разместите подпись</div>
                    <p class="text-sm text-green-800">Перетащите подпись в нужное место на документе</p>
                </div>
                <div class="bg-purple-50 p-6 rounded-lg">
                    <div class="text-purple-600 font-bold mb-2">3. Скачайте результат</div>
                    <p class="text-sm text-purple-800">Получите подписанный PDF документ</p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const pdfInput = document.getElementById('pdfInput');
        const signatureInput = document.getElementById('signatureInput');
        const submitBtn = document.getElementById('submitBtn');
        const pdfPreview = document.getElementById('pdfPreview');
        const signaturePreview = document.getElementById('signaturePreview');
        const pdfDropZone = document.getElementById('pdfDropZone');
        const signatureDropZone = document.getElementById('signatureDropZone');
        const pdfDragOverlay = document.getElementById('pdfDragOverlay');
        const signatureDragOverlay = document.getElementById('signatureDragOverlay');
        
        function updateSubmitButton() {
            submitBtn.disabled = !(pdfInput.files.length > 0 && signatureInput.files.length > 0);
        }
        
        // PDF file handler
        pdfInput.addEventListener('change', function(e) {
            handleFileSelect(this.files[0], 'pdf');
            updateSubmitButton();
        });
        
        // Signature image handler
        signatureInput.addEventListener('change', function(e) {
            handleFileSelect(this.files[0], 'signature');
            updateSubmitButton();
        });
        
        // Handle file selection
        function handleFileSelect(file, type) {
            if (!file) return;
            
            if (type === 'pdf') {
                if (file.type !== 'application/pdf') {
                    showError('Пожалуйста, выберите PDF файл');
                    return;
                }
                if (file.size > 10 * 1024 * 1024) {
                    showError('PDF файл слишком большой (макс. 10 МБ)');
                    return;
                }
                
                document.getElementById('pdfFileName').textContent = file.name;
                document.getElementById('pdfFileSize').textContent = formatFileSize(file.size);
                pdfPreview.classList.remove('hidden');
                pdfInput.files = createFileList(file);
            } else {
                const validImageTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validImageTypes.includes(file.type)) {
                    showError('Пожалуйста, выберите изображение в формате JPG или PNG');
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    showError('Изображение слишком большое (макс. 2 МБ)');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('signatureImage').src = e.target.result;
                    signaturePreview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
                signatureInput.files = createFileList(file);
            }
        }
        
        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // Create FileList object
        function createFileList(file) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            return dataTransfer.files;
        }
        
        // Remove PDF file
        window.removePdfFile = function() {
            pdfInput.value = '';
            pdfPreview.classList.add('hidden');
            updateSubmitButton();
        }
        
        // Remove signature file
        window.removeSignatureFile = function() {
            signatureInput.value = '';
            signaturePreview.classList.add('hidden');
            updateSubmitButton();
        }
        
        // Show error message
        function showError(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50';
            errorDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(errorDiv);
            setTimeout(() => errorDiv.remove(), 3000);
        }
        
        // Drag & Drop functionality for PDF
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            pdfDropZone.addEventListener(eventName, preventDefaults, false);
            signatureDropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        // Highlight drop zone
        ['dragenter', 'dragover'].forEach(eventName => {
            pdfDropZone.addEventListener(eventName, highlightPDF, false);
            signatureDropZone.addEventListener(eventName, highlightSignature, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            pdfDropZone.addEventListener(eventName, unhighlightPDF, false);
            signatureDropZone.addEventListener(eventName, unhighlightSignature, false);
        });
        
        // Handle drop
        pdfDropZone.addEventListener('drop', handlePDFDrop, false);
        signatureDropZone.addEventListener('drop', handleSignatureDrop, false);
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        function highlightPDF() {
            pdfDragOverlay.classList.remove('hidden');
        }
        
        function unhighlightPDF() {
            pdfDragOverlay.classList.add('hidden');
        }
        
        function highlightSignature() {
            signatureDragOverlay.classList.remove('hidden');
        }
        
        function unhighlightSignature() {
            signatureDragOverlay.classList.add('hidden');
        }
        
        function handlePDFDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const file = files[0];
                if (file.type === 'application/pdf') {
                    handleFileSelect(file, 'pdf');
                } else {
                    showError('Пожалуйста, перетащите PDF файл');
                }
            }
            updateSubmitButton();
        }
        
        function handleSignatureDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                const file = files[0];
                const validImageTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                
                if (validImageTypes.includes(file.type)) {
                    handleFileSelect(file, 'signature');
                } else {
                    showError('Пожалуйста, перетащите изображение (JPG или PNG)');
                }
            }
            updateSubmitButton();
        }
        
        // File type validation on form submit
        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            if (pdfInput.files.length === 0) {
                e.preventDefault();
                showError('Пожалуйста, выберите PDF файл');
                return;
            }
            
            if (signatureInput.files.length === 0) {
                e.preventDefault();
                showError('Пожалуйста, выберите изображение подписи');
                return;
            }
            
            const pdfFile = pdfInput.files[0];
            if (pdfFile.type !== 'application/pdf') {
                e.preventDefault();
                showError('Выбранный файл не является PDF');
                return;
            }
            
            const signatureFile = signatureInput.files[0];
            const validImageTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validImageTypes.includes(signatureFile.type)) {
                e.preventDefault();
                showError('Изображение подписи должно быть в формате JPG или PNG');
                return;
            }
        });
        
        // Initial button state
        updateSubmitButton();
    });
    </script>
</x-app-layout>