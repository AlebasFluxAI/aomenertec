<?php

namespace App\Notifications\Alert;

use App\Channels\WhatsAppChannel;
use App\Http\Resources\V1\UserNotificationPayload;
use App\Notifications\WhatsAppMessage;
use Illuminate\Notifications\Notification;

class AlertNotification extends Notification
{
    private $code;

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function __construct()
    {
        $this->code = rand(100000, 999999);
    }

    public function via($notifiable)
    {
        return ["database", WhatsAppChannel::class];
    }

    public function toDatabase()
    {
        return new UserNotificationPayload(
            "Alerta de consumo de cliente",
            "v1.admin.client.monitoring",
            "interna",
            1,
            "client"
        );
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
