<?php

namespace App\Notifications\Alert;

use App\Channels\WhatsAppChannel;
use App\Http\Resources\V1\UserNotificationPayload;
use App\Notifications\WhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AlertControlNotification extends Notification
{
    private $code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $clientAlert;
    public $client;
    public function __construct($clientAlert)
    {
        $this->clientAlert = $clientAlert;
        $this->client = $this->clientAlert->client;
        $this->code = rand(100000, 999999);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ["database", WhatsAppChannel::class];
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
        /*$client_alert_configuration = $this->clientAlert->clientAlertConfiguration;
        $digital_output = $client_alert_configuration->clientDigitalOutput()->get();
        $name_outputs = [];
        foreach ($digital_output as $output){
            array_push($name_outputs, $output->name);
        }
        $outputs = implode(", ", $name_outputs);
        */
        $template = 'alert_control_success';
        return (new WhatsAppMessage())
            ->to($notifiable->phone)
            ->template_name($template)
            ->params([($this->client->alias ?? $this->client->name), $this->clientAlert->clientAlertConfiguration->getVariableName(),
                $this->clientAlert->value, $this->clientAlert->created_at->format('d F H:i'),
                "https://aom.enerteclatam.com/v1/administrar/clientes/alertas/" . $this->clientAlert->client_id,
            ]);
    }
}
