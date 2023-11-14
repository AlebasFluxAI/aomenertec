<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Invoice;

use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Invoice\InvoiceIndexGuestClientService;
use App\Models\V1\Client;
use Livewire\Component;
use function view;

class   InvoiceIndexGuestClientComponent extends Component
{
    public $has_client_code;
    public $model;
    private $invoiceDetailsGuestClientService;

    public function __construct($id = null)
    {
        $this->invoiceDetailsGuestClientService = InvoiceIndexGuestClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Client $client)
    {
        $this->invoiceDetailsGuestClientService->mount($this, $client);
    }

    public function render()
    {
        return view('livewire.v1.admin.invoicing.invoice.invoice-index-guest-client', ["data" => $this->getData()])->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->invoiceDetailsGuestClientService->getData($this);

    }

}
