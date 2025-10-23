<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use App\Models\SchoolSetting;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a sample user (or create one if none exists)
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@dreamy.edu',
                'password' => bcrypt('password'),
            ]);
        }

        // Get or create school setting
        $schoolSetting = SchoolSetting::first();
        if (!$schoolSetting) {
            $schoolSetting = SchoolSetting::create([
                'name' => 'Dreamy School Philippines',
                'down_payment' => 5000,
                'due_day_of_month' => 10,
            ]);
        }

        // Create sample activity log entries
        $activities = [
            [
                'log_name' => 'default',
                'description' => 'School settings updated',
                'causer_id' => $user->id,
                'causer_type' => User::class,
                'subject_id' => $schoolSetting->id,
                'subject_type' => SchoolSetting::class,
                'properties' => json_encode([
                    'changes' => [
                        'name' => 'Dreamy School Philippines',
                        'down_payment' => 5000
                    ],
                    'ip_address' => '127.0.0.1'
                ]),
                'created_at' => Carbon::now()->subHours(2),
            ],
            [
                'log_name' => 'payment',
                'description' => 'Payment settings updated',
                'causer_id' => $user->id,
                'causer_type' => User::class,
                'subject_id' => $schoolSetting->id,
                'subject_type' => SchoolSetting::class,
                'properties' => json_encode([
                    'changes' => [
                        'down_payment' => 5000,
                        'due_day_of_month' => 10
                    ],
                    'ip_address' => '127.0.0.1'
                ]),
                'created_at' => Carbon::now()->subHours(1),
            ],
            [
                'log_name' => 'user',
                'description' => 'User logged in',
                'causer_id' => $user->id,
                'causer_type' => User::class,
                'subject_id' => $user->id,
                'subject_type' => User::class,
                'properties' => json_encode([
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]),
                'created_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'log_name' => 'enrollment',
                'description' => 'Student enrollment confirmed',
                'causer_id' => $user->id,
                'causer_type' => User::class,
                'subject_id' => 1,
                'subject_type' => 'App\\Models\\Student',
                'properties' => json_encode([
                    'student_name' => 'John Doe',
                    'program' => 'STEM',
                    'academic_term' => '2024-2025 1st Semester'
                ]),
                'created_at' => Carbon::now()->subMinutes(15),
            ],
            [
                'log_name' => 'payment',
                'description' => 'Payment received',
                'causer_id' => $user->id,
                'causer_type' => User::class,
                'subject_id' => 1,
                'subject_type' => 'App\\Models\\Invoice',
                'properties' => json_encode([
                    'amount' => 5000,
                    'payment_method' => 'Cash',
                    'invoice_number' => 'INV-20241201-0001'
                ]),
                'created_at' => Carbon::now()->subMinutes(5),
            ],
        ];

        foreach ($activities as $activity) {
            Activity::create($activity);
        }
    }
}
