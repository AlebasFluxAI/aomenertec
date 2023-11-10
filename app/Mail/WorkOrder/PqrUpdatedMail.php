<?php

namespace App\Mail\WorkOrder;

use App\Http\Resources\V1\Icon;
use App\Models\V1\Pqr;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PqrUpdatedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $pqr;
    private $notifiable;

    public function __construct($notifiable, Pqr $pqr)
    {
        $this->pqr = $pqr;
        $this->notifiable = $notifiable;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.v1.pqr_change', [
            "pqr" => $this->pqr,
            "user" => $this->notifiable,
            "logo_url" => Icon::getUserIconUser($this->notifiable),
            "url_details" => route("administrar.v1.peticiones.detalles", $this->pqr->id)
        ])->subject("Cambio en PQRS")
            ->to($this->notifiable->email);
    }
}
