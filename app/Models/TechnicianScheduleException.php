<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicianScheduleException extends Model
{
    protected $fillable = [
        'technician_id',
        'type',
        'override_status',
        'start_at',
        'end_at',
        'notes',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}
