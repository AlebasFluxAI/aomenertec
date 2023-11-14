<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use App\Models\V1\Invoice;
use Livewire\Component;

class ClientInvoiceIndexService extends Singleton
{

    public function mount(Component $component, $client)
    {
        $component->model = $client;
        $component->client = $client;
    }

    public function getData(Component $component)
    {
        return $component->model->invoices;
    }

    public function hasPaymentRegister($invoiceId)
    {

        return !Invoice::find($invoiceId)->paymentRecord;

    }

}
