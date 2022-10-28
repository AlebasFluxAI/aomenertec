<?php

namespace App\Notifications\Alert;

use App\Channels\WhatsAppChannel;
use App\Http\Resources\V1\UserNotificationPayload;
use App\Mail\WorkOrder\PqrUpdatedMail;
use App\Mail\WorkOrder\WorkOrderUpdatedMail;
use App\Notifications\WhatsAppMessage;
use Illuminate\Notifications\Notification;

class PqrUpdatedNotification extends Notification
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
        return ["mail", WhatsAppChannel::class];
    }

    public function toMail($notifiable)
    {
        return (new PqrUpdatedMail($notifiable, $this->pqr));
    }

    public function toWhatsApp($notifiable)
    {
        $template = 'pqr_changed';

        return (new WhatsAppMessage())
            ->to($notifiable->phone)
            ->template_name($template)
            ->params([$this->pqr->description,
                __("pqr." . $this->pqr->status),
                route("administrar.v1.peticiones.detalles", $this->pqr->id)
            ]);
    }
}
