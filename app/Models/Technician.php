<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Technician extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'duty_status',
        'manual_override',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_technician')
            ->withPivot('assignment_type', 'assigned_at')
            ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(TechnicianSchedule::class);
    }

    public function scheduleExceptions()
    {
        return $this->hasMany(TechnicianScheduleException::class);
    }

    /**
     * Priority 1: Manual Override
     * Priority 2: Active Schedule Exception (Lembur -> On Duty, Izin -> Off Duty)
     * Priority 3: Auto Shift Schedule (Matching current date & time)
     * Priority 4: Saved duty_status fallback
     */
    /**
     * Single Source of Truth for Technician Duty State Resolution.
     * Evaluated in real-time based on current datetime (date + hour + minute).
     *
     * Hierarchy:
     * 1. Active Schedule Exception (Popup Lembur & Izin: start_at <= now < end_at)
     * 2. Edit Technician Manual Override (if specified)
     * 3. Imported Shift Schedule (schedules table: matching date & time)
     * 4. Base Status Fallback from Edit Technician
     */
    public function resolveDutyState(?Carbon $now = null): array
    {
        $now = $now ?? Carbon::now();

        // 1. Active Schedule Exception (Lembur -> On Duty, Izin/Sakit/Cuti -> Off Duty during start_at <= now < end_at)
        $activeException = $this->relationLoaded('scheduleExceptions')
            ? $this->scheduleExceptions
                ->filter(fn($exc) => $exc->start_at && $exc->start_at <= $now && $exc->end_at && $exc->end_at > $now)
                ->sortByDesc('start_at')
                ->first()
            : $this->scheduleExceptions()
                ->where('start_at', '<=', $now)
                ->where('end_at', '>', $now)
                ->latest('start_at')
                ->first();

        if ($activeException) {
            return [
                'status' => $activeException->override_status,
                'source' => $activeException->type . ' (' . $activeException->override_status . ')',
            ];
        }

        // 2. Edit Technician Manual Override (if set)
        if (!empty($this->manual_override)) {
            return [
                'status' => $this->manual_override,
                'source' => 'Edit Technician (' . $this->manual_override . ')',
            ];
        }

        // 3. Imported Shift Schedule (schedules table matching today & time, including cross-midnight shifts)
        $todayDate     = $now->toDateString();
        $yesterdayDate = $now->copy()->subDay()->toDateString();
        $currentTime   = $now->toTimeString();

        $activeShift = $this->relationLoaded('schedules')
            ? $this->schedules
                ->filter(function ($s) use ($todayDate, $yesterdayDate, $currentTime) {
                    $sDate = Carbon::parse($s->shift_date)->toDateString();
                    if ($s->start_time === '00:00:00' && $s->end_time === '00:00:00') return false;

                    if ($sDate === $todayDate) {
                        if ($s->start_time <= $s->end_time) {
                            return $currentTime >= $s->start_time && $currentTime < $s->end_time;
                        }
                        // Cross-midnight shift starting today (e.g. 22:00 - 06:00, current time 23:00)
                        return $currentTime >= $s->start_time;
                    }

                    if ($sDate === $yesterdayDate) {
                        // Cross-midnight shift starting yesterday (e.g. 22:00 - 06:00, current time 02:00)
                        if ($s->start_time > $s->end_time) {
                            return $currentTime < $s->end_time;
                        }
                    }

                    return false;
                })
                ->first()
            : $this->schedules()
                ->where(function ($q) use ($todayDate, $yesterdayDate, $currentTime) {
                    $q->where(function ($q1) use ($todayDate, $currentTime) {
                        $q1->whereDate('shift_date', $todayDate)
                           ->where(function ($q1sub) use ($currentTime) {
                               $q1sub->where(function ($qNorm) use ($currentTime) {
                                   $qNorm->whereColumn('start_time', '<=', 'end_time')
                                         ->where('start_time', '<=', $currentTime)
                                         ->where('end_time', '>', $currentTime)
                                         ->where(function ($sub) {
                                             $sub->where('start_time', '!=', '00:00:00')
                                                 ->orWhere('end_time', '!=', '00:00:00');
                                         });
                               })->orWhere(function ($qCross) use ($currentTime) {
                                   $qCross->whereColumn('start_time', '>', 'end_time')
                                          ->where('start_time', '<=', $currentTime);
                               });
                           });
                    })
                    ->orWhere(function ($q2) use ($yesterdayDate, $currentTime) {
                        $q2->whereDate('shift_date', $yesterdayDate)
                           ->whereColumn('start_time', '>', 'end_time')
                           ->where('end_time', '>', $currentTime);
                    });
                })
                ->first();

        if ($activeShift) {
            return [
                'status' => 'On Duty',
                'source' => ($activeShift->shift_name ?: 'Shift') . ' (On Duty)',
            ];
        }

        $hasScheduleToday = $this->relationLoaded('schedules')
            ? $this->schedules->filter(fn($s) => Carbon::parse($s->shift_date)->toDateString() === $todayDate)->isNotEmpty()
            : $this->schedules()->whereDate('shift_date', $todayDate)->exists();

        if ($hasScheduleToday) {
            return [
                'status' => 'Off Duty',
                'source' => 'Jadwal Off (Off Duty)',
            ];
        }

        // 4. Base Status Fallback from Edit Technician
        $baseStatus = $this->attributes['duty_status'] ?? 'Off Duty';
        return [
            'status' => $baseStatus,
            'source' => 'Edit Technician (' . $baseStatus . ')',
        ];
    }

    public function getEffectiveDutyStatusAttribute(): string
    {
        return $this->resolveDutyState()['status'];
    }

    /** Overriding duty_status accessor so everywhere accessing $tech->duty_status gets effective duty status */
    public function getDutyStatusAttribute($value): string
    {
        return $this->getEffectiveDutyStatusAttribute();
    }

    public function getDutySourceLabelAttribute(): string
    {
        return $this->resolveDutyState()['source'];
    }

    /** Active task location from the newest non-closed ticket assignment. */
    public function getActiveTaskLocationAttribute(): string
    {
        $ticket = $this->tickets()
            ->whereNotIn('status', ['Closed', 'Rejected', 'Cancelled', 'Completed'])
            ->latest('ticket_technician.created_at')
            ->with('asset')
            ->first();

        if (!$ticket) {
            return 'No Active Assignment';
        }

        return $ticket->room ?: ($ticket->asset?->room ?: 'No Active Assignment');
    }

    public function getActiveTasksCountAttribute(): int
    {
        return $this->tickets()
            ->whereNotIn('status', ['Closed', 'Rejected', 'Cancelled', 'Completed'])
            ->count();
    }

    public function getCompletedTasksCountAttribute(): int
    {
        return $this->tickets()
            ->whereIn('status', ['Closed', 'Completed'])
            ->count();
    }

    public function scopeOnDuty($query)
    {
        $onDutyIds = static::all()->filter(fn($t) => $t->effective_duty_status === 'On Duty')->pluck('id');
        return $query->whereIn('id', $onDutyIds);
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', trim($this->name));
        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
        }
        return strtoupper(substr($this->name, 0, 2));
    }
}
