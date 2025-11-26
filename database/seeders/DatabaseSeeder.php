<?php

namespace Database\Seeders;

use App\Models\GlobalProperties;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $data = [
            'refresh.music',
            'refresh.audiobooks',
            'refresh.full',
        ];

        foreach ($data as $item) {
            GlobalProperties::create([
                'key' => $item,
            ]);
        }

    }
}
