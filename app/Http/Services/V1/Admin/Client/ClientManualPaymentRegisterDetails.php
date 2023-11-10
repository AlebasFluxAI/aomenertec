<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use App\Models\V1\Invoice;
use Livewire\Component;

class ClientManualPaymentRegisterDetails extends Singleton
{


    public function mount(Component $component, Invoice $invoice)
    {
        $component->fill([
            "model" => $invoice,
            "register" => $invoice->paymentRecord,
        ]);
    }


}
