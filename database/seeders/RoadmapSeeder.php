<?php

namespace Database\Seeders;

use App\Models\Roadmap;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoadmapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if statuses already exist
        if (Roadmap::count() > 0) {
            return;
        }

        // Create default statuses
        Roadmap::create([
            'name' => 'Open',
            'color' => '#22C55E', // Green
            'is_active' => true,
            'sort_order' => 0,
        ]);

        Roadmap::create([
            'name' => 'Closed',
            'color' => '#6B7280', // Gray
            'is_active' => false, // Closed should always be inactive
            'sort_order' => 1,
        ]);
    }
}
