@extends('layouts.admin')

@section('header', 'Управление пользователями')
@section('subheader', 'Редактирование ролей и управление доступом')

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
                <span class="ml-1 text-sm font-medium text-gray-500">Пользователи</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Имя</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Роль</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата регистрации</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-600">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if(auth()->user()->id == $user->id)
                            <span class="text-sm px-2 py-1 bg-gray-100 text-gray-800 rounded">
                                {{ $user->role == 'admin' ? 'Администратор' : 'Пользователь' }}
                            </span>
                        @else
                            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <select name="role" onchange="this.form.submit()" class="text-sm border rounded px-2 py-1">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Пользователь</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Администратор</option>
                                    <option value="support" {{ $user->role == 'support' ? 'selected' : '' }}>Поддержка</option>
                                </select>
                            </form>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d.m.Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if(auth()->user()->id != $user->id)
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirmDeleteUser()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-gray-400 text-sm">(это вы)</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Пагинация -->
    @if($users->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t">
        {{ $users->links() }}
    </div>
    @endif
</div>

<script>
function confirmDeleteUser() {
    return confirm('Вы уверены, что хотите удалить этого пользователя? Все его заявки также будут удалены. Это действие нельзя отменить.');
}
</script>
@endsection