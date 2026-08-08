<?php

namespace Database\Seeders;

use App\Models\Technician;
use Illuminate\Database\Seeder;

class TechniciansSeeder extends Seeder
{
    public function run(): void
    {
        $technicians = [
            'Susanto',
            'Hutami',
            'Lisa',
            'Zaky',
            'Syarif',
            'Ghazali',
            'Syiefa',
        ];

        foreach ($technicians as $name) {
            Technician::firstOrCreate(['name' => $name]);
        }
    }
}
