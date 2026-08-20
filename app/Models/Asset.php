<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'asset_code',
        'asset_name',
        'brand',
        'type',
        'serial_number',
        'room',
        'procurement_year',
        'status',
        'description',
        'user_id',
    ];

    protected $casts = [
        'procurement_year' => 'string',
    ];

    public function getProcurementYearAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return substr((string) $value, 0, 4);
    }

    public function preventives()
    {
        return $this->hasMany(Preventive::class, 'asset_code', 'asset_code')->orderBy('schedule_date', 'desc');
    }

    public function correctives()
    {
        return $this->hasMany(Corrective::class, 'asset_code', 'asset_code')->orderBy('repair_date', 'desc');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class)->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    // existing relationships remain
}

