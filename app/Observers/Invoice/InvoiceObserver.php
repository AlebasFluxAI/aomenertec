<?php

namespace App\Observers\Invoice;

use App\Models\V1\Invoice;

class InvoiceObserver
{
    /**
     * Handle the "created" event.
     *
     * @param mixed $models
     */
    public function creating(Invoice $invoice)
    {
        $invoice->code = "IN-" . Invoice::count() + 1;
        if ($invoice->client and !$invoice->admin) {
            $invoice->admin_id = $invoice->client->admin_id;
        }
    }

}
