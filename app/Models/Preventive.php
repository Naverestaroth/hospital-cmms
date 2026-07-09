<?php

namespace App\Models;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;

class Preventive extends Model
{
    protected $fillable = [

        'asset_id',

        'schedule_date',

        'technician',

        'status',

        'notes',

    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
