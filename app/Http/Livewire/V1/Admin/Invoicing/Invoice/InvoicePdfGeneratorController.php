<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Invoice;

use App\Models\V1\Client;
use App\Models\V1\Invoice;
use App\Models\V1\NetworkOperator;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;

class InvoicePdfGeneratorController extends Component
{
    public function getPdf(Invoice $invoice)
    {

        return $this->downloadFile($invoice);
    }

    private function downloadFile(Invoice $invoice)
    {
        $data = json_decode($invoice->pdf_data, true);
        if ($data){
            $network_operator = NetworkOperator::find($data['network_operator']['id']);
            $pdf = Pdf::loadView('reports.client_invoice', [
                "image_chart_url" => $data['image_chart_url'],
                'value' => (object)$data['value'],
                'json' => (object)$data['json'],
                'monthly_data' => (object)$data['monthly_data'],
                'client' => Client::find($data['client']['id']),
                'network_operator' => $network_operator,
                'admin' => $network_operator->admin,
                'fees' => (object)$data['fees'],
                'other_fees' => (object)$data['other_fees'],
                'bar_code' => $data['bar_code'],
                'qr_code' => $data['qr_code'],
                'other_data' => $data['other_data'],
            ]); //load view page
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download('Factura-' . $invoice->code . '.pdf');
        } else {

        }

    }

    public function getPdfId($subdomain, Invoice $invoice)
    {
        return $this->downloadFile($invoice);
    }

}
