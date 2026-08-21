<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'google_email', 'phone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is Kepala IPSRS.
     */
    public function isKepalaIpsrs(): bool
    {
        return $this->role === 'kepala_ipsrs';
    }

    /**
     * Check if user is Developer.
     */
    public function isDeveloper(): bool
    {
        return $this->role === 'developer' || session('developer_mode', false);
    }

    /**
     * Check if user is Teknisi.
     */
    public function isTeknisi(): bool
    {
        return $this->role === 'teknisi';
    }

    // Auto‑create linked technician when a teknisi user is created
    protected static function booted(): void
    {
        static::created(function ($user) {
            if ($user->role === 'teknisi') {
                // Create a technician record linked to this user if it doesn't exist
                \App\Models\Technician::firstOrCreate(
                    ['user_id' => $user->id],
                    ['name' => $user->name]
                );
            }
        });
        // Also handle role changes to/from teknisi on update
        static::updated(function ($user) {
            if ($user->isTeknisi()) {
                \App\Models\Technician::firstOrCreate(
                    ['user_id' => $user->id],
                    ['name' => $user->name]
                );
            } else {
                // If role changed away from teknisi, optionally delete linked technician
                \App\Models\Technician::where('user_id', $user->id)->delete();
            }
        });

        static::deleting(function ($user) {
            \App\Models\Technician::where('user_id', $user->id)->delete();
        });
    }



    /**
     * Get associated technician record if any.
     */
    public function technician()
    {
        return $this->hasOne(Technician::class);
    }

    /**
     * Check if user has any of given roles.
     */
    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }
}


