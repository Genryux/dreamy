<?php

namespace Database\Seeders;

use App\Models\AboutSection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AboutSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutSection::create([
            'heading' => 'About us',
            'description' => 'Founded by Dreamy School Korea, a prestigious Christian institution, we instill timeless faith values in our students during a rapidly changing era, nurturing innovative and creative talents poised to lead the Philippines and Asia.',
            'image_path' => 'images/ab.jpg',
            'is_active' => true,
            'order' => 0,
        ]);
    }
}
