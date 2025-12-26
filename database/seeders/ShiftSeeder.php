<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shift;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Shift 1: 10:00 - 17:00 WIB
        Shift::firstOrCreate(
            ['name' => 'Shift 1'],
            [
                'start_time' => '10:00:00',
                'end_time' => '17:00:00',
                'is_active' => true,
            ]
        );

        // Create Shift 2: 17:00 - 00:00 WIB (next day)
        Shift::firstOrCreate(
            ['name' => 'Shift 2'],
            [
                'start_time' => '17:00:00',
                'end_time' => '00:00:00',
                'is_active' => true,
            ]
        );
    }
}
