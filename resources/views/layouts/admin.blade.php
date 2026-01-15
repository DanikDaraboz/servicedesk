<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Админ-панель')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Навигация админки -->
    <nav class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.index') }}" class="font-bold text-lg">
                        <i class="fas fa-cogs mr-2"></i>Админ-панель
                    </a>
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('admin.index') }}" 
                           class="{{ request()->routeIs('admin.index') ? 'bg-gray-900' : 'hover:bg-gray-700' }} px-3 py-2 rounded-md">
                            <i class="fas fa-ticket-alt mr-2"></i>Заявки
                        </a>
                        <a href="{{ route('admin.users.index') }}" 
                           class="{{ request()->routeIs('admin.users.*') ? 'bg-gray-900' : 'hover:bg-gray-700' }} px-3 py-2 rounded-md">
                            <i class="fas fa-users mr-2"></i>Пользователи
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="hover:bg-gray-700 px-3 py-2 rounded-md">
                            <i class="fas fa-home mr-2"></i>Дашборд
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-white">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Основной контент -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Хлебные крошки -->
        <div class="mb-6">
            @yield('breadcrumbs')
        </div>
        
        <!-- Заголовок -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">@yield('header', 'Админ-панель')</h1>
            <p class="text-gray-600 mt-2">@yield('subheader')</p>
        </div>
        
        <!-- Сообщения -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif
        
        <!-- Контент -->
        @yield('content')
    </main>
</body>
</html>