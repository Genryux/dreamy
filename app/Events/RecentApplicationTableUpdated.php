<?php

namespace App\Events;

use App\Models\Applicants;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RecentApplicationTableUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $application;
    public $total_applications;

    /**
     * Create a new event instance.
     */
    public function __construct(Applicants $applicant, $total_applications)
    {
        // Load the applicationForm relationship and convert to array with relationship data
        $this->application = $applicant->load('applicationForm')->toArray();
        $this->total_applications = $total_applications;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('fetching-recent-applications'),
        ];
    }
}
