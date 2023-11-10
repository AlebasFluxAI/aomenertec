<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Invoice;

use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Invoice\DetailsInvoiceService;
use App\Models\V1\Invoice;
use Livewire\Component;
use function view;

class InvoiceDetailsComponent extends Component
{
    public $model;
    public $public_key;
    private $detailsInvoiceService;

    public function __construct($id = null)
    {
        $this->detailsInvoiceService = DetailsInvoiceService::getInstance();
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
