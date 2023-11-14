<?php

namespace App\Notifications\Alert;

use App\Channels\WhatsAppChannel;
use App\Http\Resources\V1\UserNotificationPayload;
use App\Mail\WorkOrder\WorkOrderUpdatedMail;
use App\Notifications\WhatsAppMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request;

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

        return ["database", "mail", WhatsAppChannel::class];

    }

    public function toDatabase()
    {

        return new UserNotificationPayload(
            "La orden de trabajo " . $this->workOrder->id . " creada por ti ha sido actualizada - Nuevo estado " . __("work_order." . $this->workOrder->status),
            "administrar.v1.ordenes_de_servicio.detalle",
            "interna",
            $this->workOrder->id,
            "workOrder"
        );

    }

    public function toMail($notifiable)
    {
        return (new WorkOrderUpdatedMail($this->workOrder));
    }

    public function toWhatsApp($notifiable)
    {
        $template = 'work_order_changed';

        return (new WhatsAppMessage())
            ->to($notifiable->phone)
            ->template_name($template)
            ->params([$this->workOrder->id . " " . $this->workOrder->description,
                __("work_order." . $this->workOrder->status),
                Request::getHost() . "/v1/administrar/ordenes_de_servicio/detalle/", $this->workOrder->id
            ]);
    }
}
