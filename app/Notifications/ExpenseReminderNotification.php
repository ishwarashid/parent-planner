<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Expense;

class ExpenseReminderNotification extends Notification
{
    use Queueable;

    protected $expense;

    /**
     * Create a new notification instance.
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $expense = $this->expense;
        $childName = $expense->child->name;
        $amount = number_format($expense->amount, 2);
        $description = $expense->description;

        return (new MailMessage)
            ->subject('Expense Reminder for ' . $childName)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('This is a reminder for an expense:')
            ->line('Child: ' . $childName)
            ->line('Description: ' . $description)
            ->line('Amount: 
 . $amount)
            ->action('View Expense', url(route('expenses.show', $expense)))
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
            'expense_id' => $this->expense->id,
            'child_name' => $this->expense->child->name,
            'description' => $this->expense->description,
            'amount' => $this->expense->amount,
        ];
    }
}
