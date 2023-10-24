<?php

namespace App\Observers\V1;

use App\Models\V1\Invoice;
use App\Models\V1\InvoicePaymentRegistration;

class InvoicePaymentRegistrationObserver
{
    public function created(InvoicePaymentRegistration $invoicePaymentRegistration)
    {
        $invoicePaymentRegistration->invoice->update([
            "payment_status" => Invoice::PAYMENT_STATUS_APPROVED
        ]);
    }

    public function creating(InvoicePaymentRegistration $invoicePaymentRegistration)
    {
        if ($invoicePaymentRegistration->payment_method == InvoicePaymentRegistration::PAYMENT_METHOD_CASH) {
            $invoicePaymentRegistration->reference = "cash";
        }
        $invoicePaymentRegistration->total = $invoicePaymentRegistration->invoice->total;

    }
}
