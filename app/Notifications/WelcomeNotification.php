<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bem-vindo ao Monitor de Preços!')
            ->greeting('Olá, ' . $notifiable->name . '!')
            ->line('Obrigado por se registrar em nossa plataforma.')
            ->line('Agora você pode acompanhar os preços dos produtos e receber alertas personalizados.')
            ->action('Acesse sua conta', route('account.dashboard'))
            ->line('Se você tiver alguma dúvida, entre em contato conosco.');
    }
}