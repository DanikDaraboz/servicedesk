<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ИСПРАВЛЕНО: админ видит все заявки, обычный пользователь - только свои
        if (Auth::user()->role === 'admin') {
            $tickets = Ticket::with('user')->latest()->get();
        } else {
            $tickets = Ticket::where('user_id', Auth::id())->get();
        }
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:technical,billing,support,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'description' => 'required|string',
            'due_date' => 'nullable|date',
        ]);

        $ticket = Ticket::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'user_id' => Auth::id(),
            'status' => 'new',
        ]);

        return redirect()->route('tickets.index')->with('success', 'Заявка "' . $ticket->title . '" успешно создана!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        // ИСПРАВЛЕНО: админ может смотреть любую заявку
        if (Auth::user()->role !== 'admin' && $ticket->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этой заявке.');
        }

        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        // ИСПРАВЛЕНО: админ может редактировать любую заявку
        if (Auth::user()->role !== 'admin' && $ticket->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этой заявке.');
        }

        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // ИСПРАВЛЕНО: админ может обновлять любую заявку
        if (Auth::user()->role !== 'admin' && $ticket->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этой заявке.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:technical,billing,support,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'description' => 'required|string',
            'due_date' => 'nullable|date',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('success', 'Заявка "' . $ticket->title . '" успешно обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        // ИСПРАВЛЕНО: админ может удалять любую заявку
        if (Auth::user()->role !== 'admin' && $ticket->user_id !== Auth::id()) {
            abort(403, 'У вас нет доступа к этой заявке.');
        }

        $ticketTitle = $ticket->title;
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Заявка "' . $ticketTitle . '" успешно удалена!');
    }
}