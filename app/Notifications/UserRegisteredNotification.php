<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserRegisteredNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail']; // Отправляем уведомление по email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Новый пользователь зарегистрирован')
            ->line("Пользователь {$this->user->name} зарегистрировался.")
            ->line("Email: {$this->user->email}")
            ->action('Подтвердить пользователя', url("/admin/users/{$this->user->id}/approve"))
            ->line('Или отклонить регистрацию.');
    }
}
