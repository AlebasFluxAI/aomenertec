<?php

namespace App\Notifications\Alert;

use App\Channels\WhatsAppChannel;
use App\Http\Resources\V1\UserNotificationPayload;
use App\Notifications\WhatsAppMessage;
use Illuminate\Notifications\Notification;

class ServerAlertNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */


    public function via($notifiable)
    {
        return WhatsAppChannel::class;
    }


    public function toWhatsApp($notifiable)
    {
        $template = 'alert_v1';

        return (new WhatsAppMessage())
            ->to($notifiable->phone)
            ->template_name($template)
            ->params([]);
    }
}
