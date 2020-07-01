<?php

namespace App\Notifications;

use App\Membership;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class MembershipRequest extends Notification
{
    use Queueable;
    protected $guest; //usuario invitado que realizó la solicitud de afiliación
    protected $membership; //registro de la solicitud de afiliación
    protected $titleNotification;
    protected $descriptionNotificacion;
    protected $typeNotification;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($typeRecieved, $titleRecieved, $descriptionRecieved, Membership $membershipRecieved, User $guestRecieved)
    {
        $this->typeNotification = $typeRecieved;
        $this->titleNotification = $titleRecieved;
        $this->descriptionNotificacion = $descriptionRecieved;
        $this->guest = $guestRecieved;
        $this->membership = $membershipRecieved;
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
        $notificationArray = [
            "title" => $this->titleNotification,
            "description" => $this->descriptionNotificacion,
            'type' => $this->typeNotification,
            "guest" => $this->guest,
            "membership" => $this->membership,
        ];
        return $notificationArray;
    }
}
