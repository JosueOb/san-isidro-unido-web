<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;


class MembershipRequest extends Notification
{
    use Queueable;
    protected $neighbor;
    // public $post;
    // protected $titleNotification;
    // protected $messageNotification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    // public function __construct($titleNotification = null, $messageNotification = null)
    public function __construct(User $neighborRecieved)
    {
        $this->neighbor = $neighborRecieved;
    //     $this->titleNotification = $titleNotification;
    //     $this->messageNotification = $messageNotification;
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
        // $title_noti = $this->titleNotification;
        // $description_noti = $this->messageNotification;
        $notificationArray = [
            "title" => 'Solicitud de afiliación',
            "description" => $this->neighbor->getFullName().' ah solicitado afilización',
            'type'=>'membership_reported',
            "neighbor" => $this->neighbor,
        ];
        return $notificationArray;
    }
}
