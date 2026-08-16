<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicianSchedule extends Model
{
    protected $fillable = [
        'technician_id',
        'shift_name',
        'shift_date',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'shift_date' => 'date',
    ];

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}
