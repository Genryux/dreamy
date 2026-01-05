<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageNotice;

class HomepageNoticeSeeder extends Seeder
{
    public function run(): void
    {
        HomepageNotice::truncate();

        HomepageNotice::create([
            'message' => '2026th School Year Admission - Enroll Now! | Limited Slots Available | Enroll Today!',
            'bg_color' => '#C8A165',
            'text_color' => '#FFFFFF',
            'link_url' => null,
            'is_scrolling' => true,
            'is_dismissible' => true,
            'is_active' => true,
            'starts_at' => null,
            'ends_at' => null,
            'order' => 1,
        ]);
    }
}
