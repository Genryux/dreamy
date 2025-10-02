<?php

namespace App\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PrivateQueuedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $title,
        public string $message,
        public ?string $url = null,
        public ?string $sharedId = null
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     * Saves to database AND broadcasts to private user channel
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast']; // Queued for performance - saves to DB and broadcasts to private channel
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line($this->message)
            ->when($this->url, function ($mail) {
                return $mail->action('View Details', $this->url);
            })
            ->line('Thank you for using our application!');
    }

    /**
     * Get the channels the event should broadcast on.
     * Uses private channel specific to the user
     */
    public function broadcastOn()
    {
        // Return a closure that will be called with the notifiable
        return function ($notifiable) {
            return new \Illuminate\Broadcasting\Channel('user.' . $notifiable->id);
        };
    }

    /**
     * Get the data to broadcast.
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the database representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'shared_id' => $this->sharedId, // Include shared ID for matching with immediate notifications
            'user_id' => $notifiable->id, // Store user ID for reference
        ];
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
            'user_id' => $notifiable->id, // Include user ID for private channel routing
        ];
    }


    /**
     * Customize the broadcast data
     */
    public function broadcastWith(): array
    {
        return [
            'id' => 'private-queued-' . time() . '-' . uniqid(),
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
