<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Event;

class EventUpdatedNotification extends Notification
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $event = $this->event;
        $childName = $event->child ? $event->child->name : 'No child assigned';
        $startTime = $event->start ? \Carbon\Carbon::parse($event->start)->format('M d, Y H:i A') : 'Not set';
        $endTime = $event->end ? \Carbon\Carbon::parse($event->end)->format('M d, Y H:i A') : 'Not set';

        return (new MailMessage)
            ->subject('Event Updated: ' . $event->title)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('An event has been updated:')
            ->line('Title: ' . $event->title)
            ->line('Child: ' . $childName)
            ->line('Description: ' . ($event->description ?? 'No description'))
            ->line('Start Time: ' . $startTime)
            ->line('End Time: ' . $endTime)
            ->line('Status: ' . ($event->status ?? 'Scheduled'))
            ->action('View Event', url(route('calendar.index')))
            ->line('Thank you for using Parent Planner!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'title' => $this->event->title,
            'child_name' => $this->event->child ? $this->event->child->name : null,
            'start' => $this->event->start,
            'end' => $this->event->end,
            'updated_at' => $this->event->updated_at,
        ];
    }
}