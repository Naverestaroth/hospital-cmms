<?php

namespace Database\Seeders;

use App\Models\Technician;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeknisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('TEKNISI_EMAIL', 'teknisi@hospital.com');
        $password = env('TEKNISI_PASSWORD', 'Teknisi2026!');

        $user = User::where('email', $email)->orWhere('role', 'teknisi')->first();

        if (! $user) {
            $user = User::create([
                'name' => 'Teknisi IPSRS',
                'email' => $email,
                'role' => 'teknisi',
                'password' => $password,
                'google_email' => 'teknisi.google@hospital.com',
                'phone' => '081234567890',
            ]);
        } else {
            $user->update([
                'email' => $email,
                'role' => 'teknisi',
                'password' => $password,
            ]);
        }


        // Link with Technician record
        $technician = Technician::where('user_id', $user->id)
            ->orWhere('email', $email)
            ->orWhere('name', 'Teknisi IPSRS')
            ->first();

        if (! $technician) {
            $technician = Technician::first();
            if ($technician) {
                $technician->update(['user_id' => $user->id]);
            } else {
                Technician::create([
                    'name' => 'Teknisi IPSRS',
                    'email' => $email,
                    'phone' => '081234567890',
                    'duty_status' => 'On Duty',
                    'user_id' => $user->id,
                ]);
            }
        } else {
            $technician->update([
                'user_id' => $user->id,
                'email' => $email,
            ]);
        }
    }
}
