@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Мои заявки</h1>
        <a href="{{ route('tickets.create') }}" class="bg-blue-500 hover:bg-blue-700 text-gray-700 font-bold py-2 px-4 rounded">
            Создать заявку
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($tickets->isEmpty())
        <div class="bg-gray-100 p-6 rounded text-center">
            <p class="text-gray-600">У вас пока нет заявок.</p>
            <a href="{{ route('tickets.create') }}" class="text-blue-500 hover:text-blue-700 mt-2 inline-block">
                Создать первую заявку
            </a>
        </div>
    @else
        <div class="bg-white shadow overflow-hidden rounded-md">
            <ul class="divide-y divide-gray-200">
                @foreach($tickets as $ticket)
                <li>
                    <div class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <a href="{{ route('tickets.show', $ticket) }}" class="text-lg font-medium text-blue-600 hover:text-blue-900">
                                    {{ $ticket->title }}
                                </a>
                                <p class="mt-1 text-sm text-gray-600">{{ $ticket->description }}</p>
                                <div class="mt-2 flex flex-wrap items-center text-sm text-gray-500 gap-4">
                                    <div>Категория: {{ $ticket->category }}</div>
                                    
                                    <div class="inline-flex items-center">
                                        Приоритет:
                                        @if($ticket->priority == 'low')
                                            <span class="px-2 ml-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Низкий
                                            </span>
                                        @elseif($ticket->priority == 'medium')
                                            <span class="px-2 ml-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Средний
                                            </span>
                                        @elseif($ticket->priority == 'high')
                                            <span class="px-2 ml-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                Высокий
                                            </span>
                                        @else
                                            <span class="px-2 ml-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Срочный
                                            </span>
                                        @endif
                                    </div>

                                    <div class="inline-flex items-center">
                                        Статус:
                                        @if($ticket->status == 'new')
                                            <span class="px-2 ml-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Новая
                                            </span>
                                        @elseif($ticket->status == 'in_progress')
                                            <span class="px-2 ml-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                В обработке
                                            </span>
                                        @elseif($ticket->status == 'completed')
                                            <span class="px-2 ml-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Выполнена
                                            </span>
                                        @else
                                            <span class="px-2 ml-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Отклонена
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <span class="text-sm text-gray-500">
                                    {{ $ticket->created_at->format('d.m.Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection