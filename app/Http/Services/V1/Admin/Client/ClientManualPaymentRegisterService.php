<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use App\Models\V1\Invoice;
use App\Models\V1\InvoicePaymentRegistration;
use Livewire\Component;

class ClientManualPaymentRegisterService extends Singleton
{


    public function mount(Component $component, Invoice $invoice)
    {
        $component->fill([
            "model" => $invoice,
            "client" => $invoice->client,
            "payment_methods" => InvoicePaymentRegistration::paymentMethodKeyValue()
        ]);
    }


    public function registerPayment(Component $component)
    {

        $component->validate([
            'evidence' => 'image|max:10240', // 1MB Max
        ]);

        $paymentRecord = $component->model->paymentRecord()->create(
            [

                "payment_method" => $component->payment_method,
                "reference" => $component->reference,
                "other_payment_method" => $component->other_payment_method,
                "bank" => $component->bank,
            ]
        );
        $paymentRecord->buildOneImageFromFile("evidence", $component->evidence);
        $component->redirectRoute("v1.admin.client.manual_payment", ["client" => $component->client->id]);

    }


}
