<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Invoice;

use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Invoice\InvoiceDetailsGuestClientService;
use App\Models\V1\Invoice;
use Livewire\Component;
use function view;

class InvoiceDetailsGuestComponent extends Component
{
    public $model;
    public $data = [];
    private $detailsInvoiceService;

    public function __construct($id = null)
    {
        $this->detailsInvoiceService = InvoiceDetailsGuestClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Invoice $invoice)
    {
        $this->detailsInvoiceService->mount($this, $invoice);

    }

    public function render()
    {
        return view('livewire.v1.admin.invoicing.invoice.details-invoice')->extends('layouts.v1.app');
    }

}
