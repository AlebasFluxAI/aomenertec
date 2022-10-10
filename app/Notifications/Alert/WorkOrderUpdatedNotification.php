<?php

namespace App\Notifications\Alert;

use App\Channels\WhatsAppChannel;
use App\Http\Resources\V1\UserNotificationPayload;
use App\Notifications\WhatsAppMessage;
use Illuminate\Notifications\Notification;

class WorkOrderUpdatedNotification extends Notification
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
        if ($this->workOrder->createdBy) {
            return ["database"];
        }
        return [];
    }

    public function toDatabase()
    {
        if ($this->workOrder->createdBy) {
            return new UserNotificationPayload(
                "La orden de trabajo " . $this->workOrder->id . " creada por ti ha sido actualizada - Nuevo estado " . __("work_order." . $this->workOrder->status),
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
