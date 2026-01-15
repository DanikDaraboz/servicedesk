<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    // Для админов
    if ($user->role === 'admin') {
        $openTicketsCount = \App\Models\Ticket::where('status', 'new')->count();
        $inProgressTicketsCount = \App\Models\Ticket::where('status', 'in_progress')->count();
        $closedTicketsCount = \App\Models\Ticket::whereIn('status', ['completed', 'rejected'])->count();
        $recentTickets = \App\Models\Ticket::with('user')->latest()->take(5)->get();
        $totalUsers = \App\Models\User::count();
    } else {
        // Для обычных пользователей
        $openTicketsCount = $user->tickets()->where('status', 'new')->count();
        $inProgressTicketsCount = $user->tickets()->where('status', 'in_progress')->count();
        $closedTicketsCount = $user->tickets()->whereIn('status', ['completed', 'rejected'])->count();
        $recentTickets = $user->tickets()->latest()->take(5)->get();
        $totalUsers = null;
    }
    
    return view('dashboard', compact(
        'openTicketsCount',
        'inProgressTicketsCount',
        'closedTicketsCount',
        'recentTickets',
        'totalUsers'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::middleware(['auth'])->group(function () {
    Route::resource('tickets', TicketController::class);
    
    // Админские маршруты
    Route::prefix('admin')->name('admin.')->group(function () {
        // Основная админка
        Route::get('/', [AdminController::class, 'index'])->name('index');
        
        // Управление тикетами
        Route::put('/tickets/{ticket}/status', [AdminController::class, 'updateStatus'])->name('tickets.updateStatus');
        Route::delete('/tickets/{ticket}', [AdminController::class, 'destroyTicket'])->name('tickets.destroy');
        
        // Управление пользователями
        Route::get('/users', [AdminController::class, 'users'])->name('users.index');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    });
});