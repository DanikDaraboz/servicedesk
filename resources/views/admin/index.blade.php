@extends('layouts.admin')

@section('header', 'Все заявки')
@section('subheader', 'Управление всеми заявками системы')

@section('breadcrumbs')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <i class="fas fa-home mr-2"></i>Главная
            </a>
        </li>
        <li aria-current="page">
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="ml-1 text-sm font-medium text-gray-500">Заявки</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<!-- Сообщения -->
@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
        <i class="fas fa-check-circle mr-3"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
        <i class="fas fa-exclamation-circle mr-3"></i>
        {{ session('error') }}
    </div>
@endif

<!-- Статистика -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-ticket-alt text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Всего заявок</p>
                <p class="text-2xl font-bold">{{ $totalTickets }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Открытые</p>
                <p class="text-2xl font-bold">{{ $openTickets }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-green-100 p-3 rounded-lg">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Пользователи</p>
                <p class="text-2xl font-bold">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-100 p-3 rounded-lg">
                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-600">Сегодня</p>
                <p class="text-2xl font-bold">{{ $tickets->where('created_at', '>=', now()->startOfDay())->count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Таблица заявок -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <div class="flex justify-between items-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Список заявок
            </h3>
            <div class="flex space-x-2">
                <input type="text" placeholder="Поиск..." class="border rounded px-3 py-1 text-sm">
                <select class="border rounded px-3 py-1 text-sm">
                    <option>Все статусы</option>
                    <option>Новая</option>
                    <option>В обработке</option>
                    <option>Выполнена</option>
                    <option>Отклонена</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Автор</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Категория</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Приоритет</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($tickets as $ticket)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        #{{ $ticket->id }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $ticket->title }}</div>
                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $ticket->description }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-700">
                                    {{ strtoupper(substr($ticket->user->name, 0, 2)) }}
                                </span>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $ticket->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $ticket->category }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $priorityConfig = [
                                'low' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Низкий'],
                                'medium' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'Средний'],
                                'high' => ['class' => 'bg-orange-100 text-orange-800', 'text' => 'Высокий'],
                                'urgent' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Срочный'],
                            ];
                            $config = $priorityConfig[$ticket->priority] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => $ticket->priority];
                        @endphp
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $config['class'] }}">
                            {{ $config['text'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('admin.tickets.updateStatus', $ticket) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            @php
                                $statusConfig = [
                                    'new' => ['class' => 'bg-blue-100 text-blue-800', 'text' => 'Новая'],
                                    'in_progress' => ['class' => 'bg-yellow-100 text-yellow-800', 'text' => 'В обработке'],
                                    'completed' => ['class' => 'bg-green-100 text-green-800', 'text' => 'Выполнена'],
                                    'rejected' => ['class' => 'bg-red-100 text-red-800', 'text' => 'Отклонена'],
                                ];
                                $currentStatus = $statusConfig[$ticket->status] ?? ['class' => 'bg-gray-100 text-gray-800', 'text' => $ticket->status];
                            @endphp
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $currentStatus['class'] }}">
                                    {{ $currentStatus['text'] }}
                                </span>
                                <select name="status" onchange="this.form.submit()" 
                                        class="text-sm border rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @foreach($statusConfig as $key => $status)
                                        <option value="{{ $key }}" {{ $ticket->status == $key ? 'selected' : '' }}>
                                            {{ $status['text'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $ticket->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <a href="{{ route('tickets.show', $ticket) }}" 
                        class="text-blue-600 hover:text-blue-900" title="Просмотр">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('tickets.edit', $ticket) }}" 
                        class="text-green-600 hover:text-green-900" title="Редактировать">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="assignTicket({{ $ticket->id }})" 
                                class="text-purple-600 hover:text-purple-900" title="Назначить">
                            <i class="fas fa-user-check"></i>
                        </button>
                        <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" class="inline" 
                            onsubmit="return confirm('Вы уверены, что хотите удалить заявку #{{ $ticket->id }}? Это действие нельзя отменить.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" title="Удалить">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-3xl mb-3 text-gray-300"></i>
                        <p class="text-lg">Нет заявок</p>
                        <p class="text-sm mt-1">Все заявки обработаны</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Пагинация -->
    @if($tickets->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t">
        {{ $tickets->links() }}
    </div>
    @endif
</div>

<!-- Последние пользователи -->
<div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Последние пользователи
        </h3>
    </div>
    <div class="px-6 py-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            @foreach($users as $user)
            <div class="border rounded-lg p-4 text-center">
                <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="text-lg font-medium text-blue-600">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                </div>
                <div class="font-medium">{{ $user->name }}</div>
                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                <div class="mt-2">
                    <span class="text-xs px-2 py-1 rounded-full 
                        {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $user->role == 'admin' ? 'Админ' : 'Пользователь' }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function assignTicket(ticketId) {
    alert('Назначение заявки #' + ticketId + ' на разработку');
    // Здесь можно добавить модальное окно для назначения
}
</script>
@endsection