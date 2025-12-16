@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                <li class="text-gray-700 font-medium">
                    Заявка #{{ $ticket->id }}
                </li>
            </ol>
        </nav>

        <!-- Заголовок -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $ticket->title }}</h1>
                <div class="mt-2 flex items-center space-x-4">
                    <span class="text-sm text-gray-600">
                        Создано: {{ $ticket->created_at->format('d.m.Y H:i') }}
                    </span>
                    <span class="text-sm text-gray-600">
                        Автор: {{ $ticket->user->name }}
                    </span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('tickets.edit', $ticket) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Редактировать
                </a>
                <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Удалить эту заявку?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Удалить
                    </button>
                </form>
            </div>
        </div>

        <!-- Карточка с информацией о заявке -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Статус и приоритет -->
            <div class="px-6 py-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-4">
                        <div>
                            <span class="text-sm font-medium text-gray-500">Статус:</span>
                            @if($ticket->status == 'new')
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Новая
                                </span>
                            @elseif($ticket->status == 'in_progress')
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    В обработке
                                </span>
                            @elseif($ticket->status == 'completed')
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Выполнена
                                </span>
                            @else
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Отклонена
                                </span>
                            @endif
                        </div>
                        
                        <div>
                            <span class="text-sm font-medium text-gray-500">Приоритет:</span>
                            @if($ticket->priority == 'low')
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Низкий
                                </span>
                            @elseif($ticket->priority == 'medium')
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Средний
                                </span>
                            @elseif($ticket->priority == 'high')
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                    Высокий
                                </span>
                            @else
                                <span class="ml-2 px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Срочный
                                </span>
                            @endif
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500">Категория:</span>
                            <span class="ml-2 text-sm text-gray-900">
                                @if($ticket->category == 'technical')
                                    🛠️ Техническая
                                @elseif($ticket->category == 'billing')
                                    💰 Биллинг
                                @elseif($ticket->category == 'support')
                                    🆘 Поддержка
                                @else
                                    📋 Другое
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                
                @if($ticket->due_date)
                <div class="mt-3 sm:mt-0">
                    <div class="text-sm text-gray-500">Срок исполнения:</div>
                    <div class="text-sm font-medium text-gray-900">
                        {{ $ticket->due_date->format('d.m.Y') }}
                        @if($ticket->due_date->isPast() && $ticket->status != 'completed')
                            <span class="ml-2 text-red-600">(просрочено)</span>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Описание -->
            <div class="px-6 py-5">
                <div class="mb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Описание проблемы</h3>
                    <div class="mt-1 text-gray-700 bg-gray-50 p-4 rounded-lg">
                        {!! nl2br(e($ticket->description)) !!}
                    </div>
                </div>

                <!-- Дополнительная информация -->
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Детали заявки</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Информация о создании</h4>
                            <dl class="mt-2 space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">ID заявки:</dt>
                                    <dd class="text-sm text-gray-900">#{{ $ticket->id }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Дата создания:</dt>
                                    <dd class="text-sm text-gray-900">{{ $ticket->created_at->format('d.m.Y H:i') }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-600">Последнее обновление:</dt>
                                    <dd class="text-sm text-gray-900">{{ $ticket->updated_at->format('d.m.Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Исполнитель</h4>
                            <div class="mt-2">
                                @if($ticket->assigned_to && $ticket->assignedUser)
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($ticket->assignedUser->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $ticket->assignedUser->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $ticket->assignedUser->email }}</p>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500 italic">Не назначен</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопки действий -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                <a href="{{ route('tickets.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    ← Назад к списку
                </a>
                <div class="flex space-x-3">
                    <a href="{{ route('tickets.edit', $ticket) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Редактировать
                    </a>
                    @if($ticket->status != 'completed')
                        <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Отметить как выполненную
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection