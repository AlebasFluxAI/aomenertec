<?php

namespace App\Mail\Alert;

use App\Http\Resources\V1\Subdomain;
use App\Models\V1\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $user;
    private $clientAlert;

    public function __construct($user, $clientAlert)
    {
        $this->user = $user;
        $this->clientAlert = $clientAlert;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.v1.alert_email', [
            "client_name" => $this->user->alias ?? $this->user->name,
            "variable_name" => $this->clientAlert->clientAlertConfiguration->getVariableName(),
            "variable_value" => $this->clientAlert->value,
            "variable_time" => $this->clientAlert->created_at->format('d F H:i'),
            "detail_link" => "https://aom.enerteclatam.com/v1/administrar/clientes/alertas/" . $this->clientAlert->client_id
        ])->subject("¡¡ Nueva alerta generada !! - " . Subdomain::getTitle())
            ->to($this->user->email);
    }
}
