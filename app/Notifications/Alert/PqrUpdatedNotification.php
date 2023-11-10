<?php

namespace App\Notifications\Alert;

use App\Channels\WhatsAppChannel;
use App\Mail\WorkOrder\PqrUpdatedMail;
use App\Notifications\WhatsAppMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Request;

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
                Request::getHost() . "/v1/administrar/peticiones/detalles/" . $this->pqr->id
            ]);
    }
}
