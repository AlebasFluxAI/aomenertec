<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Invoice;

use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Invoice\IndexInvoiceService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class InvoiceIndexComponent extends Component
{
    use WithPagination;
    use FilterTrait;


    private $indexInvoicetemsService;

    public function __construct($id = null)
    {
        $this->indexInvoicetemsService = IndexInvoiceService::getInstance();
        parent::__construct($id);
    }


    public function mount()
    {
        return $this->indexInvoicetemsService->mount($this);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.invoicing.invoice.index-invoice',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexInvoicetemsService->getData($this);
    }

    public function setFilter($filterValue)
    {
        return $this->indexInvoicetemsService->setFilter($this, $filterValue);
    }
}
