<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;
    public $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
        // $user  = User::find(14);
        $code = rand(1000000, 999999);
        return (new MailMessage)
                    ->line('Forgot Password?')
                    ->line("Use code to change password  " . $code)
                    ->line('Thank you for using our application!');
    }
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
