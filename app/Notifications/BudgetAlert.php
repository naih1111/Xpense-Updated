<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BudgetAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $category;
    protected $percentage;
    protected $amount;
    protected $limit;

    /**
     * Create a new notification instance.
     */
    public function __construct($category, $percentage, $amount, $limit)
    {
        $this->category = $category;
        $this->percentage = $percentage;
        $this->amount = $amount;
        $this->limit = $limit;
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
        $message = (new MailMessage)
            ->subject('Budget Alert: ' . $this->category->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('This is a budget alert for your ' . $this->category->name . ' category.');

        if ($this->percentage >= 100) {
            $message->line('You have exceeded your budget limit of $' . number_format($this->limit, 2) . '!')
                ->line('Current spending: $' . number_format($this->amount, 2))
                ->line('Percentage used: ' . number_format($this->percentage, 1) . '%');
        } else {
            $message->line('You are approaching your budget limit of $' . number_format($this->limit, 2) . '.')
                ->line('Current spending: $' . number_format($this->amount, 2))
                ->line('Percentage used: ' . number_format($this->percentage, 1) . '%');
        }

        return $message
            ->action('View Budget Details', url('/budgets'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'category_id' => $this->category->id,
            'percentage' => $this->percentage,
            'amount' => $this->amount,
            'limit' => $this->limit,
        ];
    }
} 