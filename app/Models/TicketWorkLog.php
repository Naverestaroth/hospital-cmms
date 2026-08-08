<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketWorkLog extends Model
{
    protected $fillable = [
        'ticket_id',
        'performed_by',
        'content',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
