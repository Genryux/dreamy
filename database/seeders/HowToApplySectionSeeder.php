<?php

namespace Database\Seeders;

use App\Models\HowToApplySection;
use Illuminate\Database\Seeder;

class HowToApplySectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section = HowToApplySection::create([
            'heading' => 'How to Apply',
            'description' => 'Follow these simple steps to start your Dreamy School journey.',
            'button_text' => 'Apply Now',
            'button_link' => '/portal/register',
            'is_active' => true,
            'order' => 1,
        ]);

        $steps = [
            [
                'step_number' => 1,
                'title' => 'Submit Application',
                'description' => 'Complete the online application form and upload required documents.',
                'icon' => 'fi-rr-form',
                'order' => 1,
            ],
            [
                'step_number' => 2,
                'title' => 'Document Review',
                'description' => 'Our admissions team will review your application and documents.',
                'icon' => 'fi-rr-document-signed',
                'order' => 2,
            ],
            [
                'step_number' => 3,
                'title' => 'Assessment & Interview',
                'description' => 'Schedule and complete your assessment and interview with our academic team.',
                'icon' => 'fi-rr-comment-alt',
                'order' => 3,
            ],
            [
                'step_number' => 4,
                'title' => 'Enrollment',
                'description' => 'Finish the enrollment process and get ready to start your academic journey!',
                'icon' => 'fi-rr-graduation-cap',
                'order' => 4,
            ],
        ];

        foreach ($steps as $step) {
            $section->steps()->create($step);
        }
    }
}
