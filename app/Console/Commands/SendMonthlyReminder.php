<?php

namespace App\Console\Commands;

use App\Models\SchoolSetting;
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
    protected $description = 'Send monthly reminder to all students with unpaid invoices on the configured due day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Get the configured due day from school settings
        $dueDayOfMonth = SchoolSetting::value('due_day_of_month') ?? 10;
        $useLastDayIfShorter = SchoolSetting::value('use_last_day_if_shorter') ?? false;
        
        // Check if today is the due day for monthly reminders
        $shouldSendReminder = $this->shouldSendReminderToday($now, $dueDayOfMonth, $useLastDayIfShorter);
        
        if (!$shouldSendReminder) {
            $this->info("Today is not the configured due day ({$dueDayOfMonth}). Skipping monthly reminders.");
            return Command::SUCCESS;
        }
        
        $this->info("Today is the due day ({$dueDayOfMonth}). Sending monthly reminders...");

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

                // Create personalized message with due day context
                $message = "Hi! Our records show you still have an outstanding balance of {$formattedBalance} for {$schoolYearText}. Please check your email for the details and visit the admin office to settle it. Thank you!";

                $student->notify(new PrivateQueuedNotification(
                    "Monthly Payment Reminder",
                    $message,
                    null,
                    $sharedNotificationId
                ));

                Notification::route('broadcast', 'user.' . $student->id)
                    ->notify(new PrivateImmediateNotification(
                        "Monthly Payment Reminder",
                        $message,
                        null,
                        $sharedNotificationId,
                        'user.' . $student->id // Pass the channel
                    ));
            }

            $this->info("Monthly reminder notifications sent to {$students->count()} students on {$now->format('Y-m-d')} (due day: {$dueDayOfMonth}).");
        } else {
            $this->info("No students with unpaid invoices found.");
        }
        
        return Command::SUCCESS;
    }
    
    /**
     * Determine if reminders should be sent today based on the configured due day
     *
     * @param Carbon $today
     * @param int $dueDayOfMonth
     * @param bool $useLastDayIfShorter
     * @return bool
     */
    private function shouldSendReminderToday(Carbon $today, int $dueDayOfMonth, bool $useLastDayIfShorter): bool
    {
        $currentDay = $today->day;
        $daysInMonth = $today->daysInMonth;
        
        // If the month is shorter than the due day and we should use the last day
        if ($useLastDayIfShorter && $dueDayOfMonth > $daysInMonth) {
            return $currentDay === $daysInMonth;
        }
        
        // Otherwise, check if today matches the due day
        return $currentDay === $dueDayOfMonth;
    }
}
