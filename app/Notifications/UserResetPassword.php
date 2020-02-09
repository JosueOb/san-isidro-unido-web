<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserResetPassword extends Notification
{
    use Queueable;
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($tokenReceived)
    {
        //Se guarda el token que recibe en la variable token
        $this->token = $tokenReceived;
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
                    ->subject('Restablecer contraseña')
                    ->greeting('Hola, '.$notifiable->first_name.' '.$notifiable->last_name)
                    ->line('Hemos recibido una petición para restablecer su contraseña.')
                    ->line('Para ello, selecciona el siguiente enlace.')
                    ->action('Restablecer contraseña', url('/password/reset/'.$this->token))
                    ->line('Este enlace de restablecimiento de contraseña caducará en 60 minutos.')
                    ->line('Si no tu no has realizado esta petición, no realices nada.')
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
