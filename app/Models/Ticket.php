<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'category',
        'priority',
        'description',
        'user_id',
        'assigned_to',
        'status',
        'due_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * Get the user who created the ticket.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user assigned to the ticket.
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}