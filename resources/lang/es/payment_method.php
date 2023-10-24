<?php

use App\Models\V1\InvoicePaymentRegistration;

return [
    InvoicePaymentRegistration::PAYMENT_METHOD_OTHER => "Otro",
    InvoicePaymentRegistration::PAYMENT_METHOD_CREDIT_CARD => "Tarjeta de credito",
    InvoicePaymentRegistration::PAYMENT_METHOD_CASH => "Efectivo",
    InvoicePaymentRegistration::PAYMENT_METHOD_TRANSFER => "Transferencia",
];
