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
     * Display a listing of all tickets.
     */
    public function index()
    {
        $this->checkAdmin();
        
        $tickets = Ticket::with('user')->latest()->get();
        $users = User::all();
        
        return view('admin.index', compact('tickets', 'users'));
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

        return redirect()->route('admin.tickets.index')->with('success', 'Статус заявки обновлен!');
    }
}