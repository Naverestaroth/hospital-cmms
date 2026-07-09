<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corrective extends Model
{
    protected $fillable = [

        'ticket_id',
        'technician',
        'repair_date',
        'status',
        'notes',

    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
