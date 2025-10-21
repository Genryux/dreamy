<?php

namespace App\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ImmediateNotification extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $title,
        public string $message,
        public ?string $url = null,
        public ?string $sharedId = null,
        public ?string $broadcastChannel = null
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     * Only broadcast - no database storage, no queue, immediate delivery
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast']; // Only broadcast, immediate delivery (not queued)
    }

    public function broadcastOn()
    {
        // Ensure we always have a valid channel name
        $channelName = $this->broadcastChannel ?? 'general';
        return new Channel($channelName);
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'shared_id' => $this->sharedId, // Include shared ID for mobile app matching
        ];
    }
}
