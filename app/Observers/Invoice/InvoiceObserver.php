<?php

namespace App\Observers\Invoice;

use App\Models\V1\BillableItem;
use App\Models\V1\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

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
    }

}
