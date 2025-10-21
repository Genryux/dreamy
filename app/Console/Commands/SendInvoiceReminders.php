<?php

namespace App\Console\Commands;

use App\Models\PaymentSchedule;
use App\Notifications\InvoiceEmailNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendInvoiceReminders extends Command
{
    protected $signature = 'invoices:send-reminders';
    protected $description = 'Send invoice reminders for upcoming and overdue payments';

    public function handle()
    {
        $today = Carbon::today();
        
        // Send 5-day reminders
        $this->sendFiveDayReminders($today);
        
        // Send due date notices
        $this->sendDueDateNotices($today);
        
        // Send overdue notices
        $this->sendOverdueNotices($today);
        
        $this->info('Invoice reminders sent successfully.');
    }

    private function sendFiveDayReminders($today)
    {
        $fiveDaysFromNow = $today->copy()->addDays(5);
        
        $schedules = PaymentSchedule::where('status', 'pending')
            ->whereDate('due_date', $fiveDaysFromNow)
            ->where('installment_number', '>', 0) // Only monthly payments, not down payment
            ->with(['invoice.student.user'])
            ->get();

        foreach ($schedules as $schedule) {
            if ($schedule->invoice->student && $schedule->invoice->student->user) {
                $schedule->invoice->student->user->notify(
                    new InvoiceEmailNotification($schedule, 'reminder')
                );
            }
        }

        $this->info("Sent {$schedules->count()} five-day reminders.");
    }

    private function sendDueDateNotices($today)
    {
        $schedules = PaymentSchedule::where('status', 'pending')
            ->whereDate('due_date', $today)
            ->where('installment_number', '>', 0)
            ->with(['invoice.student.user'])
            ->get();

        foreach ($schedules as $schedule) {
            if ($schedule->invoice->student && $schedule->invoice->student->user) {
                $schedule->invoice->student->user->notify(
                    new InvoiceEmailNotification($schedule, 'due')
                );
            }
        }

        $this->info("Sent {$schedules->count()} due date notices.");
    }

    private function sendOverdueNotices($today)
    {
        $schedules = PaymentSchedule::where('status', 'pending')
            ->whereDate('due_date', '<', $today)
            ->where('installment_number', '>', 0)
            ->with(['invoice.student.user'])
            ->get();

        foreach ($schedules as $schedule) {
            if ($schedule->invoice->student && $schedule->invoice->student->user) {
                $schedule->invoice->student->user->notify(
                    new InvoiceEmailNotification($schedule, 'overdue')
                );
            }
        }

        $this->info("Sent {$schedules->count()} overdue notices.");
    }
}
