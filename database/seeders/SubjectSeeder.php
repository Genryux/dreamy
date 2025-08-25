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

            /*
    |--------------------------------------------------------------------------
    | HUMSS TRACK (program_id = 1)
    |--------------------------------------------------------------------------
    */

            // Grade 11 - 1st Semester (Core + Applied)
            ['name' => 'Oral Communication', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Komunikasyon at Pananaliksik sa Wika at Kulturang Filipino', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'General Mathematics', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Earth and Life Science', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => '21st Century Literature from the Philippines and the World', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Physical Education and Health 1', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Empowerment Technologies', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'applied', 'semester' => '1st Semester'],

            // Grade 11 - 2nd Semester
            ['name' => 'Reading and Writing', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Pagbasa at Pagsusuri ng Iba’t Ibang Teksto Tungo sa Pananaliksik', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Statistics and Probability', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Physical Science', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Introduction to Philosophy of the Human Person', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Personal Development', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Media and Information Literacy', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Practical Research 1', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'applied', 'semester' => '2nd Semester'],

            // Grade 12 - 1st Semester
            ['name' => 'Contemporary Philippine Arts from the Regions', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Understanding Culture, Society, and Politics', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Practical Research 2', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'applied', 'semester' => '1st Semester'],

            // HUMSS Specialized – Grade 12 (1st Semester)
            ['name' => 'Creative Writing / Malikhaing Pagsulat', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Creative Nonfiction', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Introduction to World Religions and Belief Systems', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Disciplines and Ideas in the Social Sciences', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],

            // Grade 12 - 2nd Semester
            ['name' => 'Inquiries, Investigations and Immersion', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'applied', 'semester' => '2nd Semester'],

            // HUMSS Specialized – Grade 12 (2nd Semester)
            ['name' => 'Disciplines and Ideas in the Applied Social Sciences', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'Philippine Politics and Governance', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'Community Engagement, Solidarity, and Citizenship', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'Trends, Networks, and Critical Thinking in the 21st Century', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'Work Immersion / Research / Career Advocacy / Culminating Activity', 'program_id' => 1, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],

            /*
    |--------------------------------------------------------------------------
    | ABM TRACK (program_id = 2)
    |--------------------------------------------------------------------------
    | Core & Applied same as HUMSS (copied with program_id = 2).
    | Specialized below:
    */

            // Grade 11 - 1st Semester (Core + Applied)
            ['name' => 'Oral Communication', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Komunikasyon at Pananaliksik sa Wika at Kulturang Filipino', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'General Mathematics', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Earth and Life Science', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => '21st Century Literature from the Philippines and the World', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Physical Education and Health 1', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Empowerment Technologies', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'applied', 'semester' => '1st Semester'],

            // Grade 11 - 2nd Semester
            ['name' => 'Reading and Writing', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Pagbasa at Pagsusuri ng Iba’t Ibang Teksto Tungo sa Pananaliksik', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Statistics and Probability', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Physical Science', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Introduction to Philosophy of the Human Person', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Personal Development', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Media and Information Literacy', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Practical Research 1', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'applied', 'semester' => '2nd Semester'],

            // Grade 12 - 1st Semester
            ['name' => 'Contemporary Philippine Arts from the Regions', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Understanding Culture, Society, and Politics', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Practical Research 2', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'applied', 'semester' => '1st Semester'],


            // Grade 12 - 2nd Semester
            ['name' => 'Inquiries, Investigations and Immersion', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'applied', 'semester' => '2nd Semester'],

            // Specialized
            ['name' => 'Applied Economics', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Business Ethics and Social Responsibility', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Fundamentals of Accountancy, Business and Management 1', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Fundamentals of Accountancy, Business and Management 2', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'Business Math', 'program_id' => 2, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'Principles of Marketing', 'program_id' => 2, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],

            /*
    |--------------------------------------------------------------------------
    | STEM TRACK (program_id = 3)
    |--------------------------------------------------------------------------
    | Core & Applied same as HUMSS (copied with program_id = 3).
    | Specialized below:
    */

            // Grade 11 - 1st Semester (Core + Applied)
            ['name' => 'Oral Communication', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Komunikasyon at Pananaliksik sa Wika at Kulturang Filipino', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'General Mathematics', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Earth and Life Science', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => '21st Century Literature from the Philippines and the World', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Physical Education and Health 1', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Empowerment Technologies', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'applied', 'semester' => '1st Semester'],

            // Grade 11 - 2nd Semester
            ['name' => 'Reading and Writing', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Pagbasa at Pagsusuri ng Iba’t Ibang Teksto Tungo sa Pananaliksik', 'program_id' => 1, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Statistics and Probability', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Physical Science', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Introduction to Philosophy of the Human Person', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Personal Development', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Media and Information Literacy', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'core', 'semester' => '2nd Semester'],
            ['name' => 'Practical Research 1', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'applied', 'semester' => '2nd Semester'],

            // Grade 12 - 1st Semester
            ['name' => 'Contemporary Philippine Arts from the Regions', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Understanding Culture, Society, and Politics', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'core', 'semester' => '1st Semester'],
            ['name' => 'Practical Research 2', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'applied', 'semester' => '1st Semester'],


            // Grade 12 - 2nd Semester
            ['name' => 'Inquiries, Investigations and Immersion', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'applied', 'semester' => '2nd Semester'],

            // Specialized
            ['name' => 'Pre-Calculus', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'Basic Calculus', 'program_id' => 3, 'grade_level' => 'Grade 11', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'General Biology 1', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'General Biology 2', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'General Chemistry 1', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'General Chemistry 2', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'General Physics 1', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '1st Semester'],
            ['name' => 'General Physics 2', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],
            ['name' => 'Research / Capstone Project', 'program_id' => 3, 'grade_level' => 'Grade 12', 'category' => 'specialized', 'semester' => '2nd Semester'],

        ];

        foreach($subjects as $subject) {
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
