<?php

namespace App\Observers\Invoice;

use App\Models\V1\InvoiceItem;

class InvoiceItemObserver
{
    /**
     * Handle the "created" event.
     *
     * @param mixed $models
     */
    public function created(InvoiceItem $invoiceItem)
    {
        $invoice_subtotal = $invoiceItem->invoice->subtotal;
        $invoice_total = $invoiceItem->invoice->total;
        $invoice_tax_total = $invoiceItem->invoice->tax_total;

        $invoice_subtotal += $invoiceItem->subtotal;
        $invoice_total += $invoiceItem->total;
        $invoice_tax_total += $invoiceItem->tax_total;

        $invoiceItem->invoice->update([
            "subtotal" => $invoice_subtotal,
            "total" => $invoice_total,
            "tax_total" => $invoice_tax_total,
        ]);
    }

}
