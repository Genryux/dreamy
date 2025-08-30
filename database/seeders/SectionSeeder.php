<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            ['name' => '11-HUMSS-A', 'program_id' => 1, 'year_level' => 'Grade 11', 'room' => null, 'total_enrolled_students' => null],
            ['name' => '11-ABM-A', 'program_id' => 2, 'year_level' => 'Grade 11', 'room' => null, 'total_enrolled_students' => null],
            ['name' => '11-STEM-A', 'program_id' => 3, 'year_level' => 'Grade 11', 'room' => null, 'total_enrolled_students' => null],
            ['name' => '12-HUMSS-A', 'program_id' => 1, 'year_level' => 'Grade 12', 'room' => null, 'total_enrolled_students' => null],
            ['name' => '12-ABM-A', 'program_id' => 2, 'year_level' => 'Grade 12', 'room' => null, 'total_enrolled_students' => null],
            ['name' => '12-STEM-A', 'program_id' => 3, 'year_level' => 'Grade 12', 'room' => null, 'total_enrolled_students' => null],
        ];

        foreach ($sections as $section) {
            Section::create([
                'name' => $section['name'],
                'program_id' => $section['program_id'],
                'year_level' => $section['year_level'],
                'room' => $section['room'],
                'total_enrolled_students' => $section['total_enrolled_students']
            ]);
        }

    }
}
