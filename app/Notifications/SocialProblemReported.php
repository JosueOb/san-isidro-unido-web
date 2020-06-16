<?php

namespace App\Notifications;

use App\Post;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SocialProblemReported extends Notification
{
    use Queueable;
    protected $problem;
    protected $neighbor;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $problemRecieved, User $neighborRecieved)
    {
        $this->problem = $problemRecieved;
        $this->neighbor = $neighborRecieved;
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
            'title'=>'Problema reportado',
            'description'=>$this->neighbor->getFullName().' ha reportado un problema',
            'post'=>$this->problem,
            'neighbor'=>$this->neighbor,
        ];
    }
}
