<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    protected $fillable = [

        'part_code',

        'part_name',

        'stock',

        'unit',

        'location',

    ];
}
