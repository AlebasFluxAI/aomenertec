<?php

namespace App\Notifications\Alert;

use App\Http\Resources\V1\UserNotificationPayload;
use App\Notifications\WhatsAppMessage;
use Illuminate\Notifications\Notification;

class
PqrNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    private $pqr;

    public function __construct($pqr)
    {
        $this->pqr = $pqr;
    }

    public function via($notifiable)
    {
        if ($this->pqr->networkOperator or
            $this->pqr->supervisor or
            $this->pqr->client) {
            return ["database"];
        }
        return [];
    }

    public function toDatabase()
    {
        if ($this->pqr->networkOperator) {
            return new UserNotificationPayload(
                "Usuario operador de red " . $this->pqr->networkOperator->name . " " . $this->pqr->networkOperator->last_name . " a levantado un nuevo PQR",
                "administrar.v1.peticiones.detalles",
                "interna",
                $this->pqr->id,
                "pqr"
            );
        }
        if ($this->pqr->supervisor) {
            return new UserNotificationPayload(
                "Usuario supervisor " . $this->pqr->supervisor->name . " " . $this->pqr->supervisor->last_name . " a levantado un nuevo PQR",
                "administrar.v1.peticiones.detalles",
                "interna",
                $this->pqr->id,
                "pqr"
            );
        }
        if ($this->pqr->client) {
            return new UserNotificationPayload(
                "Cliente " . ($this->pqr->client->alias ?? ($this->pqr->client->name . " " . $this->pqr->client->last_name)) . " a levantado un nuevo PQR",
                "administrar.v1.peticiones.detalles",
                "interna",
                $this->pqr->id,
                "pqr"
            );
        }
        return [];
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
