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
        dd($invoice->data);
        $pdf = Pdf::loadView('reports.client_invoice', $invoice->data); //load view page
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('Factura-' . $invoice->code . '.pdf');

    }
}
