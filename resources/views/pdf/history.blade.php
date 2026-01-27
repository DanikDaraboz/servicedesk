<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Documents History') }}
            </h2>
            <a href="{{ route('pdf.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('+ New Document') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Всего документов</p>
                            <p class="text-2xl font-bold">{{ $documents->total() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Подписано</p>
                            <p class="text-2xl font-bold">{{ $documents->whereNotNull('signed_pdf_path')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">В процессе</p>
                            <p class="text-2xl font-bold">{{ $documents->whereNull('signed_pdf_path')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-600">Скачано</p>
                            <p class="text-2xl font-bold">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Table -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            История документов
                        </h3>
                        <div class="flex space-x-2">
                            <input type="text" id="searchInput" placeholder="Поиск по имени файла..." 
                                   class="border rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <select id="statusFilter" class="border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all">Все статусы</option>
                                <option value="signed">Подписано</option>
                                <option value="unsigned">Не подписано</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UUID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Имя файла</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Размер</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="documentsTable">
                            @forelse($documents as $document)
                            <tr class="hover:bg-gray-50 transition-colors" data-status="{{ $document->isSigned() ? 'signed' : 'unsigned' }}" data-filename="{{ strtolower($document->original_filename) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-mono text-gray-900">{{ substr($document->uuid, 0, 8) }}...</div>
                                    <div class="text-xs text-gray-500">ID: {{ $document->id }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 truncate max-w-xs">{{ $document->original_filename }}</div>
                                            <div class="text-xs text-gray-500">Страница: {{ $document->page_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $document->created_at->format('d.m.Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $document->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($document->isSigned())
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Подписан
                                    </span>
                                    @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Черновик
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @php
                                        $size = 0;
                                        if ($document->original_pdf_path && file_exists(storage_path('app/' . $document->original_pdf_path))) {
                                            $size += filesize(storage_path('app/' . $document->original_pdf_path));
                                        }
                                        if ($document->signed_pdf_path && file_exists(storage_path('app/' . $document->signed_pdf_path))) {
                                            $size += filesize(storage_path('app/' . $document->signed_pdf_path));
                                        }
                                        $sizeInMB = $size > 0 ? round($size / 1024 / 1024, 2) : '?';
                                    @endphp
                                    {{ $sizeInMB }} MB
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <!-- View/Edit -->
                                        <a href="{{ route('pdf.editor', $document->uuid) }}" 
                                           class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-50 rounded-lg transition-colors"
                                           title="Редактировать">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        
                                        <!-- Download Dropdown -->
                                        <div class="relative group">
                                            <button class="text-green-600 hover:text-green-900 p-2 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Скачать">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                            </button>
                                            <div class="absolute right-0 bottom-full mb-1 w-48 bg-white rounded-lg shadow-lg border z-10 hidden group-hover:block">
                                                <div class="py-2">
                                                    <a href="{{ route('pdf.download', ['uuid' => $document->uuid, 'type' => 'original']) }}" 
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        Оригинал PDF
                                                    </a>
                                                    @if($document->signature_image_path)
                                                    <a href="{{ route('pdf.download', ['uuid' => $document->uuid, 'type' => 'signature']) }}" 
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                        </svg>
                                                        Изображение подписи
                                                    </a>
                                                    @endif
                                                    @if($document->isSigned())
                                                    <a href="{{ route('pdf.download', ['uuid' => $document->uuid, 'type' => 'signed']) }}" 
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center font-semibold">
                                                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Подписанный PDF
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Delete -->
                                        <form action="{{ route('pdf.destroy', $document->uuid) }}" method="POST" 
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="Удалить">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Нет документов</h3>
                                    <p class="text-gray-500 mb-6">Начните с загрузки первого документа</p>
                                    <a href="{{ route('pdf.index') }}" 
                                       class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Загрузить документ
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($documents->hasPages())
                <div class="px-6 py-4 bg-gray-50 border-t">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Показано с <span class="font-semibold">{{ $documents->firstItem() }}</span> по 
                            <span class="font-semibold">{{ $documents->lastItem() }}</span> из 
                            <span class="font-semibold">{{ $documents->total() }}</span> документов
                        </div>
                        <div class="flex space-x-2">
                            @if($documents->onFirstPage())
                                <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">◀ Назад</span>
                            @else
                                <a href="{{ $documents->previousPageUrl() }}" class="px-3 py-1 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">◀ Назад</a>
                            @endif
                            
                            <div class="flex space-x-1">
                                @foreach(range(1, min(5, $documents->lastPage())) as $page)
                                    <a href="{{ $documents->url($page) }}" 
                                       class="px-3 py-1 rounded-lg {{ $documents->currentPage() == $page ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach
                                @if($documents->lastPage() > 5)
                                    <span class="px-3 py-1">...</span>
                                    <a href="{{ $documents->url($documents->lastPage()) }}" 
                                       class="px-3 py-1 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">
                                        {{ $documents->lastPage() }}
                                    </a>
                                @endif
                            </div>
                            
                            @if($documents->hasMorePages())
                                <a href="{{ $documents->nextPageUrl() }}" class="px-3 py-1 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300">Вперёд ▶</a>
                            @else
                                <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed">Вперёд ▶</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <!-- Quick Actions -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-6">
                    <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Нужно подписать документ?
                    </h4>
                    <p class="text-blue-700 mb-4">Загрузите PDF и изображение подписи для создания электронной подписи.</p>
                    <a href="{{ route('pdf.index') }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                        Загрузить новый документ
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
                
                <div class="bg-green-50 border border-green-100 rounded-lg p-6">
                    <h4 class="font-semibold text-green-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Все подписи в одном месте
                    </h4>
                    <p class="text-green-700 mb-4">Храните историю всех подписанных документов с возможностью быстрого доступа.</p>
                    <div class="text-sm text-green-600">
                        {{ $documents->whereNotNull('signed_pdf_path')->count() }} подписанных документов
                    </div>
                </div>
                
                <div class="bg-purple-50 border border-purple-100 rounded-lg p-6">
                    <h4 class="font-semibold text-purple-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Безопасность
                    </h4>
                    <p class="text-purple-700 mb-4">Все документы хранятся защищенно и доступны только вам.</p>
                    <div class="text-sm text-purple-600">
                        Автоматическое удаление через 30 дней
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // Filter documents table
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const tableRows = document.querySelectorAll('#documentsTable tr');
        
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            
            tableRows.forEach(row => {
                const filename = row.getAttribute('data-filename') || '';
                const status = row.getAttribute('data-status') || '';
                const matchesSearch = filename.includes(searchTerm) || searchTerm === '';
                const matchesStatus = statusValue === 'all' || status === statusValue;
                
                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
    });
    </script>
</x-app-layout>