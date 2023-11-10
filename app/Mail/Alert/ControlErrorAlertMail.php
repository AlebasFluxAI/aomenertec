<?php

namespace App\Mail\Alert;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ControlErrorAlertMail extends Mailable
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
    private $outputs;


    public function __construct($user, $clientAlert, $outputs)
    {
        $this->user = $user;
        $this->clientAlert = $clientAlert;
        $this->outputs = $outputs;

    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.v1.alert_control_error_email', [
            "client_name" => $this->user->alias ?? $this->user->name,
            "variable_name" => $this->clientAlert->clientAlertConfiguration->getVariableName(),
            "variable_value" => $this->clientAlert->value,
            "variable_time" => (new Carbon($this->clientAlert->created_at))->format('d F H:i'),
            "variable_outputs" => $this->outputs,
            "detail_link" => "https://aom.enerteclatam.com/v1/administrar/clientes/alertas/" . $this->clientAlert->client_id
        ])->subject("¡¡ Nueva alerta de control generada !!")
            ->from('soporte@enerteclatam.com', 'Enertec')
            ->to($this->user->email);
    }
}
