<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KepalaIpsrsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('KEPALA_IPSRS_EMAIL', 'kepala.ipsrs@hospital.com');
        $password = env('KEPALA_IPSRS_PASSWORD', 'KepalaIpsrs2026!');

        $user = User::where('email', $email)->orWhere('role', 'kepala_ipsrs')->first();

        if (! $user) {
            User::create([
                'name' => 'Kepala IPSRS',
                'email' => $email,
                'role' => 'kepala_ipsrs',
                'password' => $password,
            ]);
        } else {
            $user->update([
                'email' => $email,
                'role' => 'kepala_ipsrs',
                'password' => $password,
            ]);
        }


    }
}
