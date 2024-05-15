<?php

namespace App\Notifications\Alert;

use App\Channels\WhatsAppChannel;
use App\Http\Resources\V1\UserNotificationPayload;
use App\Mail\Alert\ControlErrorAlertMail;
use App\Mail\Alert\ControlSuccessAlertMail;
use App\Notifications\WhatsAppMessage;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;

class AlertControlNotification extends Notification
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $clientAlert;
    public $client;
    public $template;
    public $outputs;
    public $client_alert_configuration;
    private $code;

    public function __construct($clientAlert, $template)
    {
        $this->clientAlert = $clientAlert;
        $this->client = $this->clientAlert->client;
        $this->code = rand(100000, 999999);
        $this->template = $template;

        $this->client_alert_configuration = $this->clientAlert->clientAlertConfiguration;
        $this->outputs = $this->client->digitalOutputs()->first();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ["mail", "database", WhatsAppChannel::class];
    }

    public function toMail($notifiable)
    {
        if ($this->template == "control_alert_ok") {
            return (new ControlSuccessAlertMail($notifiable, $this->clientAlert, $this->outputs == null? '-': $this->outputs->name));
        }
        return (new ControlErrorAlertMail($notifiable, $this->clientAlert,$this->outputs == null? '-': $this->outputs->name));


    }

    public function toDatabase()
    {
        return new UserNotificationPayload(
            "Se ha presentado una variable fuera de rango con accion de control en el dispositivo de usuario " . ($this->client->alias ?? $this->client->name),
            "v1.admin.client.add.alerts",
            "interna",
            $this->client->id,
            "client"
        );
    }


    public function toWhatsApp($notifiable)
    {
        $date = new Carbon($this->clientAlert->source_timestamp);
        return (new WhatsAppMessage())
            ->to($notifiable->phone)
            ->template_name($this->template)
            ->params([($this->client->alias ?? $this->client->name),
                $this->client_alert_configuration->getVariableName(),
                $this->clientAlert->value,
                $date->format('d F H:i'),
                $this->outputs == null? '-' : $this->outputs->name,
                "https://aom.enerteclatam.com/v1/administrar/clientes/alertas/" . $this->clientAlert->client_id,
                $this->outputs == null? '-' : ($this->outputs->status ? 'Activo': 'Inactivo'),

            ]);
    }
}
