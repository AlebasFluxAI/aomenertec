<?php

namespace App\Http\Services\V1\Admin\Invoicing\Invoice;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Client;
use App\Models\V1\Invoice;
use Livewire\Component;

class InvoiceIndexGuestClientService extends Singleton
{
    public function mount(Component $component, Client $client)
    {
        $component->model = $client;
    }

    public function getData(Component $component)
    {
        return $component->data = $component->model->invoices()->where("payment_status", "!=", Invoice::PAYMENT_STATUS_APPROVED)->get();

    }

}
