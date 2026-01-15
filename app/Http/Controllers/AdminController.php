<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Check if user is admin.
     */
    private function checkAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Доступ запрещен. Только для администраторов.');
        }
    }

    /**
     * Display admin dashboard.
     */
    public function index()
    {
        $this->checkAdmin();
        
        $tickets = Ticket::with('user')->latest()->paginate(10);
        $totalTickets = Ticket::count();
        $totalUsers = User::count();
        $openTickets = Ticket::where('status', 'new')->orWhere('status', 'in_progress')->count();
        $users = User::latest()->take(5)->get();
        
        return view('admin.index', compact('tickets', 'totalTickets', 'totalUsers', 'openTickets', 'users'));
    }

    /**
     * Update ticket status.
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $this->checkAdmin();
        
        $request->validate([
            'status' => 'required|in:new,in_progress,completed,rejected',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->update([
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
        ]);

        return redirect()->route('admin.index')->with('success', 'Статус заявки обновлен!');
    }
    
    /**
     * Delete a ticket (admin only).
     */
    public function destroyTicket(Ticket $ticket)
    {
        $this->checkAdmin();
        
        $ticket->delete();
        
        return redirect()->route('admin.index')->with('success', 'Заявка успешно удалена!');
    }
    
    /**
     * Display users management.
     */
    public function users()
    {
        $this->checkAdmin();
        
        $users = User::latest()->paginate(10);
        
        return view('admin.users', compact('users'));
    }

    /**
     * Update user role.
     */
    public function updateUser(Request $request, User $user)
    {
        $this->checkAdmin();
        
        $request->validate([
            'role' => 'required|in:user,admin,support',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Роль пользователя обновлена!');
    }
    
    /**
     * Delete a user (admin only).
     */
    public function destroyUser(User $user)
    {
        $this->checkAdmin();
        
        // Запрещаем удалять самого себя
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Вы не можете удалить свой собственный аккаунт!');
        }
        
        // Удаляем все тикеты пользователя
        $user->tickets()->delete();
        
        // Удаляем пользователя
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно удален!');
    }
}