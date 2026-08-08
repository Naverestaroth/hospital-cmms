<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketActivity extends Model
{
    protected $fillable = [
        'ticket_id',
        'status',
        'action',
        'performed_by',
        'notes',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
