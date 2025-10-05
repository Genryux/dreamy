<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\User;
use App\Notifications\ImmediateNotification;
use App\Notifications\PrivateImmediateNotification;
use App\Notifications\PrivateQueuedNotification;
use App\Notifications\QueuedNotification;
use App\Services\AcademicTermService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendMonthlyReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-monthly-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send monthly reminder to all students with an unpaid invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Find students with unpaid invoices
        $students = User::role('student')->whereHas('student.invoices', function ($q) {
            $q->where('status', 'unpaid');
        })->get();

        // For student - FIXED to match working test notification pattern
        if (!$students->isEmpty()) {
            // Generate a shared ID for both queued and immediate notifications
            $sharedNotificationId = 'monthly-reminder-' . time() . '-' . uniqid();

            foreach ($students as $student) {
                // Get student's unpaid invoices with academic term info
                $unpaidInvoices = $student->student->invoices()
                    ->where('status', 'unpaid')
                    ->with('academicTerm')
                    ->get();

                // Calculate total balance
                $totalBalance = $unpaidInvoices->sum('balance');

                // Get unique school years
                $schoolYears = $unpaidInvoices
                    ->pluck('academicTerm.year')
                    ->filter()
                    ->unique()
                    ->sort()
                    ->values();

                // Format balance
                $formattedBalance = '₱' . number_format($totalBalance, 2);

                // Get current academic year using the service
                $academicTermService = app(AcademicTermService::class);
                $currentTerm = $academicTermService->fetchCurrentAcademicTerm();
                $currentSchoolYear = $currentTerm ? $currentTerm->year : null;

                // Format school year message
                if ($schoolYears->count() === 0) {
                    $schoolYearText = 'your account';
                } elseif ($schoolYears->count() === 1) {
                    $singleYear = $schoolYears->first();
                    if ($currentSchoolYear && $singleYear === $currentSchoolYear) {
                        $schoolYearText = "the current academic year ({$singleYear})";
                    } elseif ($currentSchoolYear && $singleYear < $currentSchoolYear) {
                        $schoolYearText = "the previous academic year ({$singleYear})";
                    } else {
                        $schoolYearText = $singleYear;
                    }
                } else {
                    $schoolYearText = 'multiple academic years (' . $schoolYears->implode(', ') . ')';
                }

                // Create personalized message
                $message = "Hi! Our records show you still have an outstanding balance of {$formattedBalance} for {$schoolYearText}. Please visit the admin office to settle it at your convenience, either in full or partially. Thank you!";

                $student->notify(new PrivateQueuedNotification(
                    "Invoice Reminder",
                    $message,
                    null,
                    $sharedNotificationId
                ));

                Notification::route('broadcast', 'user.' . $student->id)
                    ->notify(new PrivateImmediateNotification(
                        "Invoice Reminder",
                        $message,
                        null,
                        $sharedNotificationId,
                        'user.' . $student->id // Pass the channel
                    ));
            }

            $this->info("Monthly reminder notifications sent for day {$now}.");
        } else {
            $this->info("No students with unpaid invoices found.");
        }
    }
}
