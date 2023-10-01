<?php

use App\Models\V1\Invoice;

return [
    Invoice::PAYMENT_STATUS_APPROVED => "Pago completo",
    Invoice::PAYMENT_STATUS_PENDING => "Pago pendiente",
    Invoice::PAYMENT_STATUS_ERROR => "Error de pago",
    Invoice::PAYMENT_STATUS_DECLINED => "Pago declinado",
    Invoice::TYPE_PLATFORM_USAGE => "Uso de plataforma",
    Invoice::TYPE_CONSUMPTION => "Consumo",
];
