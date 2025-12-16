@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Хлебные крошки -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('tickets.index') }}" class="text-gray-500 hover:text-blue-600">
                        Мои заявки
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('tickets.show', $ticket) }}" class="text-gray-500 hover:text-blue-600">
                        Заявка #{{ $ticket->id }}
                    </a>
                </li>
                <li class="flex items-center">
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </li>
                <li class="text-gray-700 font-medium">
                    Редактирование
                </li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold text-gray-900 mb-6">Редактировать заявку #{{ $ticket->id }}</h1>

        <!-- Карточка с формой -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
            <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="p-6 space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Основная информация -->
                <div class="space-y-6">
                    <h2 class="text-lg font-semibold text-gray-900 pb-2 border-b border-gray-200">Основная информация</h2>
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Название заявки <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="title"
                            name="title" 
                            value="{{ old('title', $ticket->title) }}"
                            required 
                            placeholder="Введите краткое название проблемы"
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                    </div>
                    
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
                                <option value="technical" {{ $ticket->category == 'technical' ? 'selected' : '' }}>🛠️ Техническая проблема</option>
                                <option value="billing" {{ $ticket->category == 'billing' ? 'selected' : '' }}>💰 Биллинг и оплата</option>
                                <option value="support" {{ $ticket->category == 'support' ? 'selected' : '' }}>🆘 Поддержка пользователей</option>
                                <option value="other" {{ $ticket->category == 'other' ? 'selected' : '' }}>📋 Другое</option>
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
                                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>🟢 Низкий</option>
                                <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>🟡 Средний</option>
                                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>🟠 Высокий</option>
                                <option value="urgent" {{ $ticket->priority == 'urgent' ? 'selected' : '' }}>🔴 Срочный</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Детали -->
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
                            placeholder="Опишите проблему максимально подробно..."
                            class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">{{ old('description', $ticket->description) }}</textarea>
                    </div>
                    
                    <!-- Срок исполнения -->
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
                                    value="{{ old('due_date', $ticket->due_date ? $ticket->due_date->format('Y-m-d') : '') }}"
                                    class="block w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Кнопки -->
                <div class="pt-6 border-t border-gray-200 flex justify-between">
                    <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Отмена
                    </a>
                    <div class="flex space-x-3">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Сохранить изменения
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection