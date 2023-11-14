<?php

namespace App\Http\Services\V1\Admin\Invoicing\Invoice;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Invoice;
use Livewire\Component;

class DetailsInvoiceService extends Singleton
{
    public function mount(Component $component, Invoice $invoices)
    {
        try {
            $wompiSecret = $invoices->client->networkOperator->wompiCredentials->wompiSecret;

        } catch (\Throwable $error) {
            $wompiSecret = config("wompi.wompi_default_public");
        }
        $component->fill([
            "model" => $invoices,
            "public_key" => $wompiSecret->public_key ?? config("wompi.wompi_default_public")
        ]);
    }


}
