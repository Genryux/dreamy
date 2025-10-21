<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('app:send-monthly-reminder')->daily();
Schedule::command('app:update-overdue-schedules')->daily();
Schedule::command('invoices:send-reminders')->daily();