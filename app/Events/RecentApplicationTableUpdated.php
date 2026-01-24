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
    public $pending_count;
    public $accepted_count;
    public $pending_documents_count;
    public $enrolled_count;

    /**
     * Create a new event instance.
     */
    public function __construct(Applicants $applicant, $total_applications, $statusCounts = [])
    {
        // Load the applicationForm relationship and convert to array with relationship data
        $this->application = $applicant->load('applicationForm')->toArray();
        $this->total_applications = $total_applications;
        $this->pending_count = $statusCounts['pending'] ?? 0;
        $this->accepted_count = $statusCounts['accepted'] ?? 0;
        $this->pending_documents_count = $statusCounts['pending_documents'] ?? 0;
        $this->enrolled_count = $statusCounts['enrolled'] ?? 0;
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
