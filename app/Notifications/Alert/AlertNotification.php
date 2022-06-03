<?php

namespace App\Notifications\Alert;

use App\Channels\SmsChannel;
use App\Channels\WhatsAppChannel;
use App\Models\Core\UserCode;
use App\Notifications\SmsMessage;
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
        return [WhatsAppChannel::class];
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
