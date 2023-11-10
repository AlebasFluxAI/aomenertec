<?php

namespace App\Notifications\Alert;

use App\Channels\WhatsAppChannel;
use App\Http\Resources\V1\UserNotificationPayload;
use App\Notifications\WhatsAppMessage;
use Illuminate\Notifications\Notification;

class AlertNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public $clientAlert;
    public $client;
    private $code;

    public function __construct($clientAlert)
    {
        $this->clientAlert = $clientAlert;
        $this->client = $this->clientAlert->client;
        $this->code = rand(100000, 999999);
    }

    public function via($notifiable)
    {
        return ["database", WhatsAppChannel::class];
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
        $template = 'alert_variable';
        return (new WhatsAppMessage())
            ->to($notifiable->phone)
            ->template_name($template)
            ->params([($this->client->alias ?? $this->client->name), $this->clientAlert->clientAlertConfiguration->getVariableName(),
                $this->clientAlert->value, $this->clientAlert->created_at->format('d F H:i'),
                "https://aom.enerteclatam.com/v1/administrar/clientes/alertas/" . $this->clientAlert->client_id,
            ]);
    }
}
