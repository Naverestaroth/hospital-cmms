<?php

namespace App\Models;
use App\Models\Asset;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [

        'ticket_code',
        'asset_id',
        'reported_by',
        'issue',
        'priority',
        'status',

    ];
    public function asset()
    {
        return $this->belongsTo(Asset::class);
        return $this->hasOne(Corrective::class);
    }
}
