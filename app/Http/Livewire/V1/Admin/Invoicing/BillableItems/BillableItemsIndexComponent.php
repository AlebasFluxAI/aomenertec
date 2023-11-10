<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\BillableItems;

use App\Http\Services\V1\Admin\Invoicing\BillableItems\IndexBillableItemsService;
use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class BillableItemsIndexComponent extends Component
{
    use WithPagination;
    use FilterTrait;


    private $indexBillableItemsService;

    public function __construct($id = null)
    {
        $this->indexBillableItemsService = IndexBillableItemsService::getInstance();
        parent::__construct($id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.invoicing.billableItems.index-billable-items',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexBillableItemsService->getData($this);
    }
}
