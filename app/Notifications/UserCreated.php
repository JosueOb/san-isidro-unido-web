<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class UserCreated extends VerifyEmailBase
{
    use Queueable;
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($passwordRecieved)
    {
        $this->password = $passwordRecieved;
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
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new MailMessage)
                    ->subject('Bienvanida a '.env('APP_NAME'))
                    ->greeting('Hola, '.$notifiable->getFullName())
                    ->line('Has sido/a registrado en nuestro sistema como '.$notifiable->getWebSystemRoles()->name.' y esta es la información para acceder:')
                    ->line('Correo: '.$notifiable->email)
                    ->line('Contraseña: '.$this->password)
                    ->action('Ingresar', $verificationUrl)
                    ->line('Recuerda cambiar tu contraseña una vez ingreses al sistema web')
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
