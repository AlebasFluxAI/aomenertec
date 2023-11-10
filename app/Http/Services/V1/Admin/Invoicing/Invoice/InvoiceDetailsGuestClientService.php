<?php

namespace App\Http\Services\V1\Admin\Invoicing\Invoice;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Invoice;
use Livewire\Component;

class InvoiceDetailsGuestClientService extends Singleton
{
    public function mount(Component $component, Invoice $invoice)
    {
        $component->model = $invoice;
        $component->data = $invoice;
        try {
            $wompiSecret = $invoice->client->networkOperator->wompiCredentials->wompiSecret;

        } catch (\Throwable $error) {

        }

        $component->public_key = $wompiSecret->public_key ?? config("wompi.wompi_default_public");

    }
}
