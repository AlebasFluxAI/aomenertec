<?php

namespace App\Mail\WorkOrder;

use App\Http\Resources\V1\Icon;
use App\Models\V1\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkOrderUpdatedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $workOrder;

    public function __construct(WorkOrder $workOrder)
    {
        $this->workOrder = $workOrder;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.v1.work_order_change', [
            "workOrder" => $this->workOrder,
            "user" => $this->workOrder->createdBy(),
            "logo_url" => Icon::getUserIconUser($this->workOrder->createdBy()),
            "url_details" => route("administrar.v1.ordenes_de_servicio.detalle", $this->workOrder->id)
        ])->subject("Cambio en orden de trabajo")
            ->to($this->workOrder->createdBy()->email);
    }
}
