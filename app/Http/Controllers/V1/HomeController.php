<?php

namespace App\Http\Controllers\V1;

use App\Jobs\V1\Enertec\Report\ClientReportSendJob;
use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Notifications\Alert\AlertControlNotification;
use App\Notifications\Alert\AlertNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\V1\User;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        return redirect('/v1/inicio');
    }

    public function healthCheck()
    {

        $invoice = Client::whereEmail("wilder.herrera@unillanos.edu.co")->first()->invoices()->create();

        $total_consumption = $invoice->client->monthlyMicrocontrollerData()
            ->where("month", now()->subMonth()->month)
            ->where("year", now()->year)
            ->first()
            ->interval_real_consumption;

        $item = $invoice->items()->create([
            "unit_total" => $invoice->client->consumptionFee(),
            "subtotal" => $invoice->client->consumptionFee() * $total_consumption,
            "total" => $invoice->client->consumptionFee() * $total_consumption,
            "tax_total" => 0.0,
            "discount" => 0.0,
            "billable_item_id" => 2,
            "quantity" => $total_consumption,
        ]);
        $invoice->update([
            "subtotal" => $item->subtotal,
            "total" => $item->total,
            "tax_total" => $item->tax_total,
        ]);
        $pdf = Pdf::loadView("mail.v1.client_invoice_email", [
            "invoice" => $invoice,
            "consumption" => $invoice->client->consumption()
        ]);
        $pdf->setPaper('LETTER');
        $pdf->render();
        $date = now()->format("d-m-Y");
        $content = $pdf->download()->getOriginalContent();
        Storage::put("public/bills/Factura_{$invoice->code}_{$date}.pdf", $content);
        return $pdf->download('test.pdf');
    }
}
