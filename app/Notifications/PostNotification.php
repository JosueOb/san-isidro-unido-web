<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Post;

class PostNotification extends Notification
{
    use Queueable;

    public $post;
    public $titleNotification;
    public $messageNotification;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $post, $titleNotification='Titulo de la Notificacion', $messageNotification = 'Contenido de la Notificacion')
    {
        //
       $this->post = $post;
       $this->titleNotification = $titleNotification;
       $this->messageNotification = $messageNotification;
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
        $post =  Post::findById($this->post->id)->with(["category", "subcategory"])->first();
        $notificationArray = [
            "title" => $this->titleNotification,
            "description" => $this->messageNotification,
            "notification_user" => $notifiable, //el usuario al que le voy a enviar 
            "post" => $post,
        ];
        return $notificationArray;
    }
}
