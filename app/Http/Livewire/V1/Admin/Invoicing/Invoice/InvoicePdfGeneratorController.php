<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Invoice;

use App\Events\ChatEvent;
use App\Http\Resources\V1\Menu;
use App\Http\Resources\V1\Subdomain;
use App\Models\V1\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use function view;

class InvoicePdfGeneratorController extends Component
{
    public function getPdf(Invoice $invoice)
    {
        $pdf = Pdf::loadView('reports.invoice', [
            "logo_url" => Subdomain::getHeaderIcon(),
            "client_name" => $invoice->admin ? $invoice->admin->name : $invoice->networkOperator->name,
            "client_document" => $invoice->admin ? $invoice->admin->identification : $invoice->networkOperator->identification,
            "client_address" => $invoice->admin ? $invoice->admin->address : $invoice->networkOperator->address,
            "client_city" => "Bogota",
            "money" => "COP",
            "notes" => "Factura recurrente por uso de plataforma",
            "items" => $invoice->items,
            "subtotal" => $invoice->subtotal,
            "total" => $invoice->total,
            "total_tax" => $invoice->tax_total,
            "payment_date" => $invoice->payment_date,
            "expiration_date" => $invoice->expiration_date,
            "currency" => $invoice->currency,
            "invoice_code" => $invoice->code,
            "invoice_payment_status" => strtoupper(__("invoice." . $invoice->payment_status)),
            "color" => $invoice->payment_status == Invoice::PAYMENT_STATUS_APPROVED ? "#3d8f5f" : "gray",
        ]); //load view page
        $pdf->setPaper("LETTER");
        return $pdf->download('Factura-' . $invoice->code . '.pdf');

    }
}
