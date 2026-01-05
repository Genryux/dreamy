<?php

namespace Database\Seeders;

use App\Models\AcademicProgramsSection;
use App\Models\AcademicProgramsItem;
use Illuminate\Database\Seeder;

class AcademicProgramsSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section = AcademicProgramsSection::create([
            'heading' => 'Academic Programs',
            'description' => 'Discover our comprehensive academic programs designed to prepare students for success',
            'is_active' => true,
            'order' => 0,
        ]);

        // STEM Track Programs
        AcademicProgramsItem::create([
            'academic_programs_section_id' => $section->id,
            'title' => 'Science, Technology, Engineering & Mathematics',
            'description' => 'Explore the world of innovation through our comprehensive STEM program.',
            'track_name' => 'STEM',
            'gradient_from' => '#1A3165',
            'gradient_to' => '#2A4A7A',
            'link_url' => null,
            'status' => 'active',
            'order' => 0,
        ]);

        // ABM Track Programs
        AcademicProgramsItem::create([
            'academic_programs_section_id' => $section->id,
            'title' => 'Accountancy, Business & Management',
            'description' => 'Develop essential business skills and entrepreneurial mindset.',
            'track_name' => 'ABM',
            'gradient_from' => '#C8A165',
            'gradient_to' => '#8B6F47',
            'link_url' => null,
            'status' => 'active',
            'order' => 1,
        ]);

        // HUMSS Track Programs
        AcademicProgramsItem::create([
            'academic_programs_section_id' => $section->id,
            'title' => 'Humanities & Social Sciences',
            'description' => 'Understand human behavior, society, and culture in depth.',
            'track_name' => 'HUMSS',
            'gradient_from' => '#C8A165',
            'gradient_to' => '#8B6F47',
            'link_url' => null,
            'status' => 'active',
            'order' => 2,
        ]);

        // GAS Track Programs
        AcademicProgramsItem::create([
            'academic_programs_section_id' => $section->id,
            'title' => 'General Academic Strand',
            'description' => 'A flexible program that allows you to explore various fields.',
            'track_name' => 'GAS',
            'gradient_from' => '#C8A165',
            'gradient_to' => '#8B6F47',
            'link_url' => null,
            'status' => 'active',
            'order' => 3,
        ]);

        // Coming Soon Example
        AcademicProgramsItem::create([
            'academic_programs_section_id' => $section->id,
            'title' => 'Arts & Design',
            'description' => 'Express your creativity through various artistic mediums.',
            'track_name' => 'AD',
            'gradient_from' => '#1A3165',
            'gradient_to' => '#2A4A7A',
            'link_url' => null,
            'status' => 'coming_soon',
            'order' => 4,
        ]);

        AcademicProgramsItem::create([
            'academic_programs_section_id' => $section->id,
            'title' => 'Sports Track',
            'description' => 'Develop athletic excellence while maintaining academic standards.',
            'track_name' => 'SPORTS',
            'gradient_from' => '#1A3165',
            'gradient_to' => '#2A4A7A',
            'link_url' => null,
            'status' => 'coming_soon',
            'order' => 5,
        ]);
    }
}
