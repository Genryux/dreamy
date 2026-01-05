<?php

namespace Database\Seeders;

use App\Models\HeroSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HeroSection::create([
            'title' => 'Dreamy School',
            'subtitle' => 'Philippines',
            'background_type' => 'video',
            'background_video_path' => 'background/Dreamy Bg-1.mp4',
            'background_image_path' => null,
            'is_active' => true,
            'order' => 0,
        ]);
    }
}
