<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Для админов показываем статистику по всем тикетам
        if ($user->role === 'admin') {
            // Используем статусы из AdminController
            $openTicketsCount = Ticket::where('status', 'new')->count();
            $inProgressTicketsCount = Ticket::where('status', 'in_progress')->count();
            $closedTicketsCount = Ticket::whereIn('status', ['completed', 'rejected'])->count();
            $recentTickets = Ticket::with('user')->latest()->take(5)->get();
            $totalUsers = User::count();
        } else {
            // Для обычных пользователей - только их тикеты
            // Используем статусы из AdminController
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
    }
}