<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Program;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get programs for reference
        $humssProgram = Program::where('code', 'HUMSS')->first();
        $abmProgram = Program::where('code', 'ABM')->first();
        $stemProgram = Program::where('code', 'STEM')->first();

        if (!$humssProgram || !$abmProgram || !$stemProgram) {
            $this->command->error('Programs not found. Please run ProgramSeeder first.');
            return;
        }

        $teachers = [
            // HUMSS Teachers (2)
            [
                'user' => [
                    'first_name' => 'Maria',
                    'last_name' => 'Santos',
                    'email' => 'maria.santos@school.edu',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
                'teacher' => [
                    'program_id' => $humssProgram->id,
                    'first_name' => 'Maria',
                    'last_name' => 'Santos',
                    'email_address' => 'maria.santos@school.edu',
                    'contact_number' => '09171234567',
                    'specialization' => 'Social Sciences and Humanities',
                    'status' => 'active',
                ]
            ],
            [
                'user' => [
                    'first_name' => 'Jose',
                    'last_name' => 'Reyes',
                    'email' => 'jose.reyes@school.edu',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
                'teacher' => [
                    'program_id' => $humssProgram->id,
                    'first_name' => 'Jose',
                    'last_name' => 'Reyes',
                    'email_address' => 'jose.reyes@school.edu',
                    'contact_number' => '09171234568',
                    'specialization' => 'Literature and Communication',
                    'status' => 'active',
                ]
            ],

            // ABM Teachers (2)
            [
                'user' => [
                    'first_name' => 'Ana',
                    'last_name' => 'Cruz',
                    'email' => 'ana.cruz@school.edu',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
                'teacher' => [
                    'program_id' => $abmProgram->id,
                    'first_name' => 'Ana',
                    'last_name' => 'Cruz',
                    'email_address' => 'ana.cruz@school.edu',
                    'contact_number' => '09171234569',
                    'specialization' => 'Business Management and Entrepreneurship',
                    'status' => 'active',
                ]
            ],
            [
                'user' => [
                    'first_name' => 'Carlos',
                    'last_name' => 'Mendoza',
                    'email' => 'carlos.mendoza@school.edu',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
                'teacher' => [
                    'program_id' => $abmProgram->id,
                    'first_name' => 'Carlos',
                    'last_name' => 'Mendoza',
                    'email_address' => 'carlos.mendoza@school.edu',
                    'contact_number' => '09171234570',
                    'specialization' => 'Accounting and Finance',
                    'status' => 'active',
                ]
            ],

            // STEM Teacher (1)
            [
                'user' => [
                    'first_name' => 'Dr. Patricia',
                    'last_name' => 'Garcia',
                    'email' => 'patricia.garcia@school.edu',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ],
                'teacher' => [
                    'program_id' => $stemProgram->id,
                    'first_name' => 'Dr. Patricia',
                    'last_name' => 'Garcia',
                    'email_address' => 'patricia.garcia@school.edu',
                    'contact_number' => '09171234571',
                    'specialization' => 'Mathematics and Physics',
                    'status' => 'active',
                ]
            ],
        ];

        foreach ($teachers as $teacherData) {
            // Create user first
            $user = User::create($teacherData['user']);
            
            // Assign teacher role
            $user->assignRole('teacher');
            
            // Create teacher record
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'employee_id' => Teacher::generateEmployeeId(),
                ...$teacherData['teacher']
            ]);

            $this->command->info("Created teacher: {$teacher->getFullNameAttribute()} ({$teacher->employee_id}) - {$teacher->specialization}");
        }

        $this->command->info('Teacher seeder completed successfully!');
        $this->command->info('Created 2 HUMSS teachers, 2 ABM teachers, and 1 STEM teacher.');
    }
}
