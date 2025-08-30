<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Subject;
use App\Models\SectionSubject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing sections and subjects
        $sections = Section::all();
        $subjects = Subject::all();

        if ($sections->isEmpty() || $subjects->isEmpty()) {
            return;
        }

        // Sample scheduling data
        $scheduleData = [
            // Grade 11 HUMSS subjects
            ['section_name' => '11-HUMSS-A', 'subject_name' => 'Oral Communication', 'room' => 'Room 101', 'days' => ['Monday', 'Wednesday'], 'start_time' => '08:00', 'end_time' => '09:00'],
            ['section_name' => '11-HUMSS-A', 'subject_name' => 'General Mathematics', 'room' => 'Room 102', 'days' => ['Tuesday', 'Thursday'], 'start_time' => '09:00', 'end_time' => '10:00'],
            ['section_name' => '11-HUMSS-A', 'subject_name' => 'Earth and Life Science', 'room' => 'Science Lab 1', 'days' => ['Friday'], 'start_time' => '10:00', 'end_time' => '12:00'],
            
            // Grade 11 ABM subjects
            ['section_name' => '11-ABM-A', 'subject_name' => 'Oral Communication', 'room' => 'Room 201', 'days' => ['Monday', 'Wednesday'], 'start_time' => '13:00', 'end_time' => '14:00'],
            ['section_name' => '11-ABM-A', 'subject_name' => 'General Mathematics', 'room' => 'Room 202', 'days' => ['Tuesday', 'Thursday'], 'start_time' => '14:00', 'end_time' => '15:00'],
            
            // Grade 11 STEM subjects
            ['section_name' => '11-STEM-A', 'subject_name' => 'Oral Communication', 'room' => 'Room 301', 'days' => ['Monday', 'Wednesday'], 'start_time' => '08:00', 'end_time' => '09:00'],
            ['section_name' => '11-STEM-A', 'subject_name' => 'General Mathematics', 'room' => 'Room 302', 'days' => ['Tuesday', 'Thursday'], 'start_time' => '09:00', 'end_time' => '10:00'],
            ['section_name' => '11-STEM-A', 'subject_name' => 'Earth and Life Science', 'room' => 'Science Lab 2', 'days' => ['Friday'], 'start_time' => '10:00', 'end_time' => '12:00'],
        ];

        foreach ($scheduleData as $schedule) {
            $section = $sections->where('name', $schedule['section_name'])->first();
            $subject = $subjects->where('name', $schedule['subject_name'])->first();

            if ($section && $subject) {
                SectionSubject::create([
                    'section_id' => $section->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => null, // Will be assigned later
                    'room' => $schedule['room'],
                    'days_of_week' => $schedule['days'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                ]);
            }
        }
    }
}
