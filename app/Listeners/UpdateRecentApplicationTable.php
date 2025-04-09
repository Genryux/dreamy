<?php

namespace App\Listeners;

use App\Events\RecentApplicationTableUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateRecentApplicationTable
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RecentApplicationTableUpdated $event): void
    {
        //
    }
}
