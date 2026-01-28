@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Заголовок с навигацией -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Хлебные крошки">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="{{ route('tickets.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Мои заявки
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linecap="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-700 md:ms-2">Создать заявку</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900">Новая заявка</h1>
            <p class="mt-2 text-sm text-gray-600">Заполните все поля для создания новой заявки в службу поддержки.</p>
        </div>

        <!-- Карточка с формой -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
            <form action="{{ route('tickets.store') }}" method="POST" class="p-6 space-y-8">
                @csrf
                
                <!-- Блок: Основная информация -->
                <div class="space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b border-gray-200">Основная информация</h2>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Название -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Название заявки <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="title"
                                name="title" 
                                required 
                                placeholder="Введите краткое название проблемы"
                                class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                        </div>
                        
                        <!-- Категория и Приоритет в одну строку на больших экранах -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Категория -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                    Категория <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="category"
                                    name="category" 
                                    required 
                                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    <option value="" disabled selected>Выберите категорию</option>
                                    <option value="technical">🛠️ Техническая проблема</option>
                                    <option value="billing">💰 Биллинг и оплата</option>
                                    <option value="support">🆘 Поддержка пользователей</option>
                                    <option value="other">📋 Другое</option>
                                </select>
                            </div>
                            
                            <!-- Приоритет -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                                    Приоритет <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    id="priority"
                                    name="priority" 
                                    required 
                                    class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                    <option value="" disabled selected>Выберите приоритет</option>
                                    <option value="low">🟢 Низкий</option>
                                    <option value="medium" selected>🟡 Средний</option>
                                    <option value="high">🟠 Высокий</option>
                                    <option value="urgent">🔴 Срочный</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Блок: Детали -->
                <div class="space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b border-gray-200">Детали заявки</h2>
                    
                    <!-- Описание -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Подробное описание <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            id="description"
                            name="description" 
                            rows="6" 
                            required 
                            placeholder="Опишите проблему максимально подробно. Укажите шаги для воспроизведения, ожидаемый и фактический результат."
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"></textarea>
                        <p class="mt-2 text-sm text-gray-500">Чем детальнее описание, тем быстрее мы сможем помочь.</p>
                    </div>
                    
                    <!-- Срок исполнения с улучшенным полем -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Желаемый срок исполнения
                            </label>
                            <div class="mt-1 relative rounded-lg shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <input 
                                    type="date" 
                                    id="due_date"
                                    name="due_date" 
                                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Оставьте пустым, если срок не критичен.</p>
                        </div>
                    </div>
                </div>

                <!-- Кнопки действий -->
                <div class="pt-6 border-t border-gray-200 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('tickets.index') }}" 
                    class="inline-flex justify-center items-center px-6 py-2 border border-gray-300 rounded-lg shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 w-full">
                        Отмена
                    </a>
                    <button type="submit" 
                    class="inline-flex justify-center items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 w-full">
                        Создать заявку
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Подсказка -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Совет по заполнению</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Для быстрого решения проблемы рекомендуем прикреплять скриншоты или логи в поле описания (можно просто вставить текст). После создания заявки вы сможете отслеживать её статус в личном кабинете.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection