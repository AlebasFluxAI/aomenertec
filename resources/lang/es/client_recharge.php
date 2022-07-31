<?php

use App\Models\V1\ClientRecharge;

return [
    ClientRecharge::PURCHASE_TYPE_STORE => "Compra en tienda",
    ClientRecharge::PURCHASE_TYPE_ONLINE => "Compra online",

    ClientRecharge::PURCHASE_PAYMENT_METHOD_CASH => "Pago en efectivo",
    ClientRecharge::PURCHASE_PAYMENT_METHOD_ONLINE => "Pago online",

    ClientRecharge::PURCHASE_PAYMENT_STATUS_COMPLETED => "Completo",
    ClientRecharge::PURCHASE_PAYMENT_STATUS_PENDING => "Pendiente",
    ClientRecharge::PURCHASE_PAYMENT_STATUS_CANCELLED => "Cancelado"
];
