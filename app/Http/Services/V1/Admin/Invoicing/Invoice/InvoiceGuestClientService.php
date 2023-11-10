<?php

namespace App\Http\Services\V1\Admin\Invoicing\Invoice;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Client;
use App\Models\V1\Invoice;
use Livewire\Component;

class InvoiceGuestClientService extends Singleton
{
    public function mount(Component $component, Invoice $invoices)
    {
        $component->fill([
            "model" => $invoices
        ]);
    }

    public function submitForm(Component $component)
    {
        $client = null;
        if ($component->client_code) {
            $client = Client::whereCode($component->client_code)->first();
        }
        if ($component->contact_identification and !$client) {
            $client = Client::whereIdentification($component->contact_identification)->first();
        }
        if (!$client) {
            $component->addError("clientError", "No se encuentra cliente con los criterios de busqueda");
            return;
        }

        $component->redirectRoute("guest.invoice-index-payment", ["client" => $client->id, "subdomain" => $component->subdomain]);

    }


}
