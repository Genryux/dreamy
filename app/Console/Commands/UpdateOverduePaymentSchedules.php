<?php

namespace App\Console\Commands;

use App\Models\PaymentSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateOverduePaymentSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-overdue-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update payment schedule statuses to overdue for past due dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue payment schedules...');

        // Find all pending schedules that are past their due date
        $overdueSchedules = PaymentSchedule::where('status', 'pending')
            ->where('due_date', '<', Carbon::now())
            ->get();

        $updatedCount = 0;

        foreach ($overdueSchedules as $schedule) {
            // Update the status (this will automatically set to 'overdue' if past due date)
            $schedule->updateStatus();
            $updatedCount++;

            $this->line("Updated schedule ID {$schedule->id} for invoice {$schedule->invoice_id} - {$schedule->description}");
        }

        if ($updatedCount === 0) {
            $this->info('No overdue schedules found.');
        } else {
            $this->info("Successfully updated {$updatedCount} overdue payment schedule(s).");
        }

        return Command::SUCCESS;
    }
}
