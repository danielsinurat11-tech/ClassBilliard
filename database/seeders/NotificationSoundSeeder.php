<?php

namespace Database\Seeders;

use App\Models\NotificationSound;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class NotificationSoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Don't create default sound - let admin upload their own
        // This seeder is kept for future use if needed
    }
}
