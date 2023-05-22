<?php

namespace App\Notifications\Alert;

use App\Channels\WhatsAppChannel;
use App\Http\Resources\V1\UserNotificationPayload;
use App\Mail\Alert\AlertMail;
use App\Notifications\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;

class AlertNotification extends Notification
{
    private $code;

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public $clientAlert;
    public $client;

    public function __construct($clientAlert)
    {
        $this->clientAlert = $clientAlert;
        $this->client = $this->clientAlert->client;
        $this->code = rand(100000, 999999);
    }

    public function via($notifiable)
    {
        return [WhatsAppChannel::class];
    }

    public function toMail($notifiable)
    {
        return (new AlertMail($notifiable, $this->clientAlert));

    }

    public function toDatabase()
    {
        return new UserNotificationPayload(
            "Se ha presentado una variable fuera de rango en el dispositivo de usuario " . ($this->client->alias ?? $this->client->name),
            "v1.admin.client.add.alerts",
            "interna",
            $this->client->id,
            "client"
        );
    }

    public function toWhatsApp($notifiable)
    {
        $template = 'access_code_v3';
        $date = new Carbon($this->clientAlert->source_timestamp);
        return (new WhatsAppMessage())
            ->to($notifiable->phone)
            ->template_name($template)
            ->params(["ad"]);
    }
}
