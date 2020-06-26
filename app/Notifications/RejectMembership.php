<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RejectMembership extends Notification
{
    use Queueable;
    protected $reason;//la razón del rechazo de la solicitud de afiliación

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($reasonRecieved)
    {
        $this->reason = $reasonRecieved;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
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
            ->subject('Bienvanida a ' . env('APP_NAME'))
            ->greeting('Hola, ' . $notifiable->getFullName())
            ->line('Tu solicitud a sido rechazada')
            ->line('Observación: ' . $this->reason)
            ->salutation('Saludos');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
