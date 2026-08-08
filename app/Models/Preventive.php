<?php

namespace App\Models;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Model;

class Preventive extends Model
{
    protected $fillable = [

        'room',
        'schedule_date',
        'asset_code',
        'asset_name',
        'brand',
        'type',
        'serial_number',
        'procurement_year',
        'good_condition',
        'problem_found',
        'checklist',
        'condition',
        'technician',
        'status',
        'notes',

    ];

    protected $casts = [
        'checklist' => 'array',
    ];

    public function getProcurementYearAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        // preventives.procurement_year tersimpan seperti YYYY-01-01, tampilkan tahun saja
        return substr((string) $value, 0, 4);
    }


    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_code', 'asset_code');
    }
}
