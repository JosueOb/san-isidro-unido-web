<?php

namespace App\Notifications;

use App\Post;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PublicationReport extends Notification
{
    use Queueable;
    protected $neighbor; //morador que reportó el problema social/emergencia
    protected $socialProblem; //problema social reportado
    protected $titleNotification;
    protected $descriptionNotificacion;
    protected $typeNotification;//'problem_reported', 'emergency_reported'

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($typeRecieved, $titleRecieved, $descriptionRecieved, Post $problemRecieved, User $neighborRecieved)
    {
        $this->typeNotification = $typeRecieved;
        $this->titleNotification = $titleRecieved;
        $this->descriptionNotificacion = $descriptionRecieved;
        $this->neighbor = $neighborRecieved;
        $this->socialProblem = $problemRecieved;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'title' => $this->titleNotification,
            'description' => $this->descriptionNotificacion,
            'type' => $this->typeNotification,
            'post' => $this->socialProblem,
            'neighbor' => $this->neighbor,
        ];
    }
}
