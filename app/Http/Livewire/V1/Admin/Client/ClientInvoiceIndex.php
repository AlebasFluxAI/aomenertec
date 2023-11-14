<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientInvoiceIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\Client;
use Livewire\Component;
use Livewire\WithPagination;

class ClientInvoiceIndex extends Component
{
    use WithPagination;
    use FilterTrait;

    public $model;
    public $client;
    private $clientInvoiceIndexService;

    public function __construct($id = null)
    {
        $this->clientInvoiceIndexService = ClientInvoiceIndexService::getInstance();
        parent::__construct($id);
    }

    public function mount(Client $client)
    {
        $this->clientInvoiceIndexService->mount($this, $client);
    }

    public function hasPaymentRegister($invoiceId)
    {

        return $this->clientInvoiceIndexService->hasPaymentRegister($invoiceId);

    }

    public function render()
    {
        return view('livewire.v1.admin.client.client-invoice-index', [
            "data" => $this->getData()
        ])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->clientInvoiceIndexService->getData($this);
    }
}
