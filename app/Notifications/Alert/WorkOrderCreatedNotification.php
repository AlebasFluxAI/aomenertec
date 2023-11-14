<?php

namespace App\Notifications\Alert;

use App\Http\Resources\V1\UserNotificationPayload;
use App\Notifications\WhatsAppMessage;
use Illuminate\Notifications\Notification;

class WorkOrderCreatedNotification extends Notification
{
    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    private $workOrder;

    public function __construct($workOrder)
    {
        $this->workOrder = $workOrder;
    }

    public function via($notifiable)
    {
        if ($this->workOrder->technician) {
            return ["database"];
        }
        return [];
    }

    public function toDatabase()
    {
        if ($this->workOrder->technician || $this->workOrder->support) {
            return new UserNotificationPayload(
                "Tienes una nueva orden de trabajo - Orden de trabajo " . $this->workOrder->id,
                "administrar.v1.ordenes_de_servicio.detalle",
                "interna",
                $this->workOrder->id,
                "workOrder"
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
