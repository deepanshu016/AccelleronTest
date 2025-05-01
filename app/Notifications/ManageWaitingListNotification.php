<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
class ManageWaitingListNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $event;
    protected $user;
    /**
     * Create a new notification instance.
     */
    public function __construct($ticket)
    {
        Log::info('Found waiting booking: in notification', ['ticket' => json_decode($ticket)]);

        $this->event = $ticket->event;
        $this->user = $ticket->user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'event_id' => $this->event->id,
            'event_name' => $this->event->title,
            'message' => "Your ticket for event {$this->event->title} has been confirmed now",
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
            //
        ];
    }
}
