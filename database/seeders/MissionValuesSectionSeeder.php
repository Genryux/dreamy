<?php

namespace Database\Seeders;

use App\Models\MissionValuesSection;
use App\Models\MissionValuesItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MissionValuesSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section = MissionValuesSection::create([
            'heading' => 'Our Mission & Values',
            'description' => 'Guiding every student to become a compassionate, innovative, and responsible leader for the future.',
            'is_active' => true,
            'order' => 0,
        ]);

        // Create the three value items
        MissionValuesItem::create([
            'mission_values_section_id' => $section->id,
            'icon' => 'fi fi-rr-bulb',
            'title' => 'Academic Excellence',
            'description' => 'We strive for the highest standards in teaching, learning, and achievement.',
            'color' => '#1A3165',
            'order' => 0,
        ]);

        MissionValuesItem::create([
            'mission_values_section_id' => $section->id,
            'icon' => 'fi fi-rr-heart',
            'title' => 'Integrity & Compassion',
            'description' => 'We nurture character, honesty, and empathy in every member of our community.',
            'color' => '#C8A165',
            'order' => 1,
        ]);

        MissionValuesItem::create([
            'mission_values_section_id' => $section->id,
            'icon' => 'fi fi-rr-globe',
            'title' => 'Global Citizenship',
            'description' => 'We prepare students to thrive and lead in a diverse, interconnected world.',
            'color' => '#1A3165',
            'order' => 2,
        ]);
    }
}
