<?php

use App\Models\V1\Invoice;

return [
    Invoice::PAYMENT_STATUS_PAID => "Pago completo",
    Invoice::PAYMENT_STATUS_PENDING => "Pago pendiente",
    Invoice::PAYMENT_STATUS_LATE => "Pago atrasado",
];
