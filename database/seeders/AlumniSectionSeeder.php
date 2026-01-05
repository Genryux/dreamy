<?php

namespace Database\Seeders;

use App\Models\AlumniSection;
use App\Models\AlumniItem;
use Illuminate\Database\Seeder;

class AlumniSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the main section
        $section = AlumniSection::create([
            'heading' => 'Alumni Success Stories',
            'description' => 'Meet some of our outstanding alumni and see where their Dreamy School journey has taken them.',
            'background_image' => null, // Will use images/graduate.jpg as fallback
            'is_active' => true,
            'order' => 1,
        ]);

        // Create the alumni items
        $items = [
            [
                'name' => 'Anna Reyes',
                'photo' => null, // Will use images/alumni1.jpg as fallback
                'class_year' => 'Class of 2020',
                'track' => 'STEM',
                'quote' => 'Now a Computer Science scholar at UP Diliman. "Dreamy School gave me the confidence and skills to pursue my dreams."',
                'order' => 1,
            ],
            [
                'name' => 'Miguel Santos',
                'photo' => null, // Will use images/alumni2.jpg as fallback
                'class_year' => 'Class of 2019',
                'track' => 'ABM',
                'quote' => 'Now a business owner and entrepreneur. "The values and leadership I learned at Dreamy School shaped my career."',
                'order' => 2,
            ],
            [
                'name' => 'Sarah Lee',
                'photo' => null, // Will use images/alumni3.jpg as fallback
                'class_year' => 'Class of 2021',
                'track' => 'HUMSS',
                'quote' => 'Now a published writer and youth advocate. "Dreamy School inspired me to find my voice and make a difference."',
                'order' => 3,
            ],
        ];

        foreach ($items as $item) {
            $section->items()->create($item);
        }
    }
}
