<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corrective extends Model
{
    protected $fillable = [
        'ticket_id',
        'repair_date',
        'jam_laporan',
        'jam_visit',
        'response_time',
        'room',
        'asset_code',
        'asset_name',
        'brand',
        'type',
        'serial_number',
        'tanggal_instal',
        'distributor',
        'service_type',
        'inspection',
        'problem',
        'solution',
        'sparepart',
        'quantity',
        'inspection_result',
        'technician',
        'user_name',
        'position',
        'notes',
    ];

    protected $casts = [
        'repair_date' => 'date:Y-m-d',
        'service_type' => 'array',
        'inspection' => 'array',
        'technician' => 'array',
    ];

    public function getTanggalInstalAttribute($value)
    {
        if (empty($value)) {
            return null;
        }

        return substr((string) $value, 0, 4);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_code', 'asset_code');
    }
}
