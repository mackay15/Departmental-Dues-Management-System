<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentAccountCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public string $tempPassword;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $tempPassword)
    {
        $this->tempPassword = $tempPassword;
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
        return (new MailMessage)
            ->subject('Welcome to COMPSSA - Your Student Account Credentials')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your student login account has been successfully created on the HTU COMPSSA Student Finance Management System (SFMS).')
            ->line('Here are your temporary login credentials:')
            ->line('**Email:** ' . $notifiable->email)
            ->line('**Temporary Password:** ' . $this->tempPassword)
            ->action('Login to Your Account', route('login'))
            ->line('For security reasons, you will be required to change your password immediately upon your first login.')
            ->line('Thank you,')
            ->line('Ho Technical University COMPSSA Administration');
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
