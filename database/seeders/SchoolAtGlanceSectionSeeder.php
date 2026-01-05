<?php

namespace Database\Seeders;

use App\Models\SchoolAtGlanceSection;
use App\Models\SchoolAtGlanceItem;
use Illuminate\Database\Seeder;

class SchoolAtGlanceSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section = SchoolAtGlanceSection::create([
            'heading' => 'School at a Glance',
            'description' => 'A quick look at what makes Dreamy School unique and outstanding.',
            'is_active' => true,
            'order' => 0,
        ]);

        // Create the four statistic items
        SchoolAtGlanceItem::create([
            'school_at_glance_section_id' => $section->id,
            'value' => '500+',
            'label' => 'Active Students',
            'bg_color' => '#1A3165',
            'text_color' => '#FFFFFF',
            'order' => 0,
        ]);

        SchoolAtGlanceItem::create([
            'school_at_glance_section_id' => $section->id,
            'value' => '95%',
            'label' => 'Graduation Rate',
            'bg_color' => '#f8f8f8',
            'text_color' => '#1A3165',
            'order' => 1,
        ]);

        SchoolAtGlanceItem::create([
            'school_at_glance_section_id' => $section->id,
            'value' => '50+',
            'label' => 'Qualified Teachers',
            'bg_color' => '#1A3165',
            'text_color' => '#FFFFFF',
            'order' => 2,
        ]);

        SchoolAtGlanceItem::create([
            'school_at_glance_section_id' => $section->id,
            'value' => '15+',
            'label' => 'Years of Excellence',
            'bg_color' => '#f8f8f8',
            'text_color' => '#1A3165',
            'order' => 3,
        ]);
    }
}
