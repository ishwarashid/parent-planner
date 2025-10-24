<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Visitation;

class VisitationCreatedNotification extends Notification
{
    use Queueable;

    protected $visitation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Visitation $visitation)
    {
        $this->visitation = $visitation;
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
        $visitation = $this->visitation;
        $childName = $visitation->child->name;
        $parentName = $visitation->parent->name;
        $startTime = \Carbon\Carbon::parse($visitation->date_start)->format('M d, Y H:i A');
        $endTime = \Carbon\Carbon::parse($visitation->date_end)->format('M d, Y H:i A');

        return (new MailMessage)
            ->subject('New Visitation Created: ' . $childName)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new visitation has been created:')
            ->line('Child: ' . $childName)
            ->line('Parent: ' . $parentName)
            ->line('Start Date: ' . $startTime)
            ->line('End Date: ' . $endTime)
            ->line('Status: ' . ($visitation->status ?? 'Scheduled'))
            ->line('Notes: ' . ($visitation->notes ?? 'No notes'))
            ->action('View Visitation', url(route('visitations.show', $visitation)))
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
            'visitation_id' => $this->visitation->id,
            'child_name' => $this->visitation->child->name,
            'parent_name' => $this->visitation->parent->name,
            'date_start' => $this->visitation->date_start,
            'date_end' => $this->visitation->date_end,
        ];
    }
}