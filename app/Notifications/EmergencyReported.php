<?php

namespace App\Notifications;

use App\Post;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmergencyReported extends Notification
{
    use Queueable;
    protected $neighbor;
    protected $emergency;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $emergencyRecieved, User $neighborRecieved)
    {
        $this->neighbor = $neighborRecieved;
        $this->emergency = $emergencyRecieved;
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
            'title'=>'Emergencia reportada',
            'description'=>$this->neighbor->getFullName().' ha reportado una emergencia',
            'post'=>$this->emergency,
            'neighbor'=>$this->neighbor,
        ];
    }
}
