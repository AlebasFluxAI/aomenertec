<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use App\Models\Traits\ClientServiceTrait;
use App\Models\V1\Client;
use App\Models\V1\Invoice;
use Livewire\Component;

class ClientManualPaymentService extends Singleton
{
    use ClientServiceTrait;

    public function mount(Component $component, Client $client)
    {
        $component->fill([
            "model" => $client
        ]);
    }

    public function getData(Component $component)
    {
        return $component->model->invoices()->where("payment_status", "!=", Invoice::PAYMENT_STATUS_APPROVED)->get();

    }

}
