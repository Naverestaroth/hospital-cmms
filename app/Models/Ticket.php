<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_code',
        'asset_id',
        'room',
        'reported_by',
        'creator_type',
        'issue',
        'work_performed',
        'equipment_completeness',
        'sent_to_workshop_date',
        'sent_by',
        'received_by_workshop',
        'returned_date',
        'returned_by',
        'received_by_user',
        'priority',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'cancellation_reason',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'sent_to_workshop_date' => 'date:Y-m-d',
        'returned_date' => 'date:Y-m-d',
    ];

    public function getLocationStatusAttribute(): string
    {
        if (!empty($this->sent_to_workshop_date) || !empty($this->sent_by)) {
            if (empty($this->returned_date)) {
                return 'In Workshop';
            }
        }
        return 'In Room';
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function technicians()
    {
        return $this->belongsToMany(Technician::class, 'ticket_technician')
            ->withPivot('assignment_type', 'assigned_at')
            ->withTimestamps();
    }

    public function activities()
    {
        return $this->hasMany(TicketActivity::class)->orderBy('created_at', 'asc');
    }

    public function workLogs()
    {
        return $this->hasMany(TicketWorkLog::class)->latest();
    }

    public function corrective()
    {
        return $this->hasOne(Corrective::class);
    }

    public function logActivity(string $action, string $performedBy, ?string $notes = null): TicketActivity
    {
        return $this->activities()->create([
            'status' => $this->status,
            'action' => $action,
            'performed_by' => $performedBy,
            'notes' => $notes,
        ]);
    }
}
