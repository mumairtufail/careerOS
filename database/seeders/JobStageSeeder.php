<?php

namespace Database\Seeders;

use App\Models\JobStage;
use Illuminate\Database\Seeder;

class JobStageSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            ['name' => 'Wishlist', 'slug' => 'wishlist', 'sort_order' => 1],
            ['name' => 'Applied', 'slug' => 'applied', 'sort_order' => 2],
            ['name' => 'Screening', 'slug' => 'screening', 'sort_order' => 3],
            ['name' => 'Interview', 'slug' => 'interview', 'sort_order' => 4],
            ['name' => 'Offer', 'slug' => 'offer', 'sort_order' => 5],
            ['name' => 'Rejected', 'slug' => 'rejected', 'sort_order' => 6],
        ];

        foreach ($stages as $stage) {
            JobStage::create(array_merge($stage, ['is_system_default' => true]));
        }
    }
}
