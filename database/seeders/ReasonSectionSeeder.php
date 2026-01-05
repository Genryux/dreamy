<?php

namespace Database\Seeders;

use App\Models\ReasonSection;
use App\Models\ReasonItem;
use Illuminate\Database\Seeder;

class ReasonSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the main section
        $section = ReasonSection::create([
            'heading' => 'Why Choose Dreamy School?',
            'description' => 'Discover what makes us the preferred choice for quality education',
            'is_active' => true,
            'order' => 1,
        ]);

        // Create the reason items
        $items = [
            [
                'title' => 'Academic Excellence',
                'description' => 'Committed to providing world-class education with proven track record of student success',
                'image' => null, // Will use images/grad.jpg as fallback
                'order' => 1,
            ],
            [
                'title' => 'Experienced Faculty',
                'description' => 'Dedicated and qualified teachers with years of experience in their respective fields',
                'image' => null, // Will use images/teaching.jpg as fallback
                'order' => 2,
            ],
            [
                'title' => 'Modern Technology',
                'description' => 'State-of-the-art facilities and technology integration for 21st-century learning',
                'image' => null, // Will use images/tech.jpg as fallback
                'order' => 3,
            ],
            [
                'title' => 'Student Support',
                'description' => 'Comprehensive guidance, counseling, and support services for every student',
                'image' => null, // Will use images/guide.jpg as fallback
                'order' => 4,
            ],
            [
                'title' => 'Values & Character',
                'description' => 'Building strong character and values alongside academic achievement',
                'image' => null, // Will use images/support.jpg as fallback
                'order' => 5,
            ],
            [
                'title' => 'Modern Facilities',
                'description' => 'Well-equipped classrooms, laboratories, and learning spaces for optimal education',
                'image' => null, // Will use images/facility.jpg as fallback
                'order' => 6,
            ],
        ];

        foreach ($items as $item) {
            $section->items()->create($item);
        }
    }
}
