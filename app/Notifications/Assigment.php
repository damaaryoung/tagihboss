<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class Assigment extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
     public function via($notifiable)
     {
         return [WebPushChannel::class];
     }
     public function toWebPush($notifiable, $notification)
     {
         return (new WebPushMessage)
             ->title('Approved!')
             ->icon('/tagihbos-transparent.png')
             ->body('Your account was approved!')
             ->action('View account', 'view_account')
             ->options(['TTL' => 1000]);
     }
}
