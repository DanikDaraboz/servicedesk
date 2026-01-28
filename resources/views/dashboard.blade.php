<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Панель управления
            </h2>
            
            <!-- Кнопка создания тикета -->
            <a href="{{ route('tickets.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Новая заявка
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Статистика в карточках -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Карточка открытых тикетов -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 p-3 rounded-lg">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Открытые заявки</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $openTicketsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Карточка тикетов в работе -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 p-3 rounded-lg">
                                <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">В работе</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $inProgressTicketsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Карточка закрытых тикетов -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 p-3 rounded-lg">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Закрытые заявки</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $closedTicketsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Приветствие -->
            <div class="mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-2">С возвращением, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600">
                            У вас {{ $openTicketsCount }} открытых заявок и {{ $inProgressTicketsCount }} в работе.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Последние тикеты -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Последние заявки</h3>
                        <a href="{{ route('tickets.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Смотреть все
                        </a>
                    </div>
                    
                    @if($recentTickets && $recentTickets->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">№</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Тема</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Создано</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentTickets as $ticket)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $ticket->id }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="hover:text-blue-600">
                                                {{ Str::limit($ticket->subject, 60) }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @php
                                                $statusConfig = [
                                                    'open' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Открыта'],
                                                    'in_progress' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'В работе'],
                                                    'closed' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Закрыта'],
                                                ];
                                                $config = $statusConfig[$ticket->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => $ticket->status];
                                            @endphp
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $config['class'] }}">
                                                {{ $config['text'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $ticket->created_at->format('d.m.Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                                Просмотр
                                            </a>
                                            @if($ticket->status !== 'closed')
                                            <a href="{{ route('tickets.edit', $ticket) }}" class="text-gray-600 hover:text-gray-900">
                                                Изменить
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Заявок пока нет</h3>
                            <p class="mt-1 text-sm text-gray-500">Создайте первую заявку в службу поддержки.</p>
                            <div class="mt-6">
                                <a href="{{ route('tickets.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Создать заявку
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Быстрые действия -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 border border-blue-100 rounded-lg p-6">
                    <h4 class="font-semibold text-blue-800 mb-3">Нужна помощь?</h4>
                    <p class="text-blue-700 mb-4">Создайте новую заявку — наша команда поможет вам как можно скорее.</p>
                    <a href="{{ route('tickets.create') }}" 
                       class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                        Создать заявку
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
                
                <div class="bg-green-50 border border-green-100 rounded-lg p-6">
                    <h4 class="font-semibold text-green-800 mb-3">Все заявки</h4>
                    <p class="text-green-700 mb-4">Просматривайте все заявки, отслеживайте статус и добавляйте комментарии.</p>
                    <a href="{{ route('tickets.index') }}" 
                       class="inline-flex items-center text-green-600 hover:text-green-800 font-medium">
                        Смотреть все заявки
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>