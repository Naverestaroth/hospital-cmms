<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    protected $fillable = [
        'name',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_technician')
            ->withPivot('assignment_type', 'assigned_at')
            ->withTimestamps();
    }
}
