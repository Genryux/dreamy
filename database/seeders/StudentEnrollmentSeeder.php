<?php

namespace Database\Seeders;

use App\Models\AcademicTerms;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Database\Seeder;

class StudentEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Only run if the per-term enrollment system is enabled
        if (!config('app.use_term_enrollments')) {
            $this->command->info('Per-term enrollments disabled. Skipping StudentEnrollmentSeeder.');
            return;
        }

        // Get the active academic term
        $activeTerm = AcademicTerms::where('is_active', true)->first();
        
        if (!$activeTerm) {
            $this->command->warn('No active academic term found. Skipping enrollment seeding.');
            return;
        }

        // Get all students
        $students = Student::all();
        
        if ($students->isEmpty()) {
            $this->command->warn('No students found. Skipping enrollment seeding.');
            return;
        }

        $created = 0;
        $existing = 0;

        foreach ($students as $student) {
            $enrollment = StudentEnrollment::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_term_id' => $activeTerm->id,
                ],
                [
                    'status' => 'pending_confirmation',
                    'program_id' => null, // Can be set later
                    'section_id' => null, // Can be set later
                ]
            );

            if ($enrollment->wasRecentlyCreated) {
                $created++;
            } else {
                $existing++;
            }
        }

        $this->command->info("Student enrollment seeding completed:");
        $this->command->info("- Created: {$created} new enrollments");
        $this->command->info("- Existing: {$existing} enrollments");
        $this->command->info("- Term: {$activeTerm->getFullNameAttribute()}");
    }
}
