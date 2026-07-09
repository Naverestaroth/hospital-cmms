<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [

        'asset_code',
        'asset_name',
        'category',
        'brand',
        'model',
        'serial_number',
        'room',
        'purchase_date',
        'status',
        'description',

    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
        ];
    }

    public function preventives()
    {
        return $this->hasMany(Preventive::class);
    }
}
