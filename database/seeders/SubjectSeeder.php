<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [

            // CORE - GRADE 11 (1ST SEMESTER)
            ['name' => 'Oral Communication', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'General Mathematics', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Earth and Life Science', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Personal Development', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Physical Education and Health 1', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],

            // CORE - GRADE 11 (2ND SEMESTER)
            ['name' => 'Reading and Writing', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Pagbasa at Pagsusuri ng Iba\'t Ibang Teksto Tungo sa Pananaliksik', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Statistics and Probability', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => '21st Century Literature from the Philippines and the World', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Physical Science', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Physical Education and Health 2', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],

            // CORE - GRADE 12 (1ST SEMESTER)
            ['name' => 'Contemporary Philippine Arts from the Regions', 'program_id' => null, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Media and Information Literacy', 'program_id' => null, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Introduction to the Philosophy of the Human Person', 'program_id' => null, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Physical Education and Health 3', 'program_id' => null, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '1st Semester'],

            // CORE - GRADE 12 (2ND SEMESTER)
            ['name' => 'Understanding Culture, Society and Politics', 'program_id' => null, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Disaster Readiness and Risk Reduction', 'program_id' => null, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Physical Education and Health 4', 'program_id' => null, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '2nd Semester'],

            // APPLIED - GRADE 11 (1ST SEMESTER)
            ['name' => 'English for Academic and Professional Purposes', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'applied', 'semester' => '1st Semester'],
            ['name' => 'Filipino sa Piling Larangan (Akademik)', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'applied', 'semester' => '1st Semester'],

            // APPLIED - GRADE 11 (2ND SEMESTER)
            ['name' => 'Practical Research 1', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'applied', 'semester' => '2nd Semester'],
            ['name' => 'Empowerment Technologies', 'program_id' => null, 'grade_level' => 'Grade 11', 'category' => 'applied', 'semester' => '2nd Semester'],

            // APPLIED - GRADE 12 (1ST SEMESTER)
            ['name' => 'Practical Research 2', 'program_id' => null, 'grade_level' => 'Grade 12', 'category' => 'applied', 'semester' => '1st Semester'],
            ['name' => 'Inquiries, Investigations and Immersion', 'program_id' => null, 'grade_level' => 'Grade 12', 'category' => 'applied', 'semester' => '1st Semester'],

            // APPLIED - GRADE 12 (2ND SEMESTER)
            ['name' => 'Entrepreneurship', 'program_id' => null, 'grade_level' => 'Grade 12', 'category' => 'applied', 'semester' => '2nd Semester'],
            ['name' => 'Work Immersion', 'program_id' => null, 'grade_level' => 'Grade 12', 'category' => 'applied', 'semester' => '2nd Semester'],


            // STEM (program_id = 3) ----------------------------------------- // 

            // SPECIALIZED - GRADE 11 (1ST SEMESTER)
            ['name' => 'Pre-Calculus', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'General Biology 1', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'General Physics 1', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '1st Semester'],

            // SPECIALIZED - GRADE 11 (2ND SEMESTER)
            ['name' => 'Basic Calculus', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'General Chemistry 1', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '2nd Semester'],

            // SPECIALIZED - GRADE 12 (1ST SEMESTER)
            ['name' => 'General Chemistry 2', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'General Biology 2', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],

            // SPECIALIZED - GRADE 12 (2ND SEMESTER)
            ['name' => 'General Physics 2', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],


            // ABM (program_id = 2) ----------------------------------------- //

            // SPECIALIZED - GRADE 11 (1ST SEMESTER)
            ['name' => 'Fundamentals of Accountancy, Business and Management 1', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Business Ethics and Social Responsibility', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Applied Economics', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '1st Semester'],

            // SPECIALIZED - GRADE 11 (2ND SEMESTER)
            ['name' => 'Fundamentals of Accountancy, Business and Management 2', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'Business Math', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'Organization and Management', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '2nd Semester'],

            // SPECIALIZED - GRADE 12 (1ST SEMESTER)
            ['name' => 'Business Finance', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Principles of Marketing', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],

            // SPECIALIZED - GRADE 12 (2ND SEMESTER)
            ['name' => 'Business Enterprise Simulation', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],


            // HUMSS (program_id = 1) ---------------------------------------- //

            // SPECIALIZED - GRADE 11 (1ST SEMESTER)
            ['name' => 'Creative Writing', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Introduction to World Religions and Belief Systems', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Disciplines and Ideas in the Social Sciences', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '1st Semester'],

            // SPECIALIZED - GRADE 11 (2ND SEMESTER)
            ['name' => 'Creative Nonfiction', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'Philippine Politics and Governance', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'Trends, Networks, and Critical Thinking in the 21st Century Culture', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '2nd Semester'],

            // SPECIALIZED - GRADE 12 (1ST SEMESTER)
            ['name' => 'Community Engagement, Solidarity, and Citizenship', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Disciplines and Ideas in the Applied Social Sciences', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],

            // SPECIALIZED - GRADE 12 (2ND SEMESTER)
            ['name' => 'HUMSS Elective', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],

        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'name' => $subject['name'],
                'program_id' => $subject['program_id'],
                'grade_level' => $subject['grade_level'],
                'category' => $subject['category'],
                'semester' => $subject['semester'],
            ]);
        }
    }
}
