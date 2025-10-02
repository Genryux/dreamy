<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class PrivateImmediateNotification extends Notification
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
     * Only broadcast to private user channel - no database storage, immediate delivery
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast']; // Only broadcast, immediate delivery (not queued)
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new \Illuminate\Broadcasting\Channel($this->broadcastChannel ?? 'user.1');
    }

    /**
     * Get the data to broadcast.
     */
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


    /**
     * Customize the broadcast data
     */
    public function broadcastWith(): array
    {
        return [
            'id' => 'private-immediate-' . time() . '-' . uniqid(),
            'type' => static::class,
            'data' => [
                'title' => $this->title,
                'message' => $this->message,
                'url' => $this->url,
                'shared_id' => $this->sharedId,
            ],
            'created_at' => now()->toISOString(),
        ];
    }

    /**
     * Get the broadcast event name
     */
    public function broadcastAs()
    {
        return 'notification';
    }
}
