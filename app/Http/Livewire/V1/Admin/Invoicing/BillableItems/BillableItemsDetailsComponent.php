<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\BillableItems;

use App\Http\Services\V1\Admin\Invoicing\BillableItems\DetailsBillableItemsService;
use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Models\V1\BillableItem;
use Livewire\Component;
use function view;

class BillableItemsDetailsComponent extends Component
{
    public $model;
    private $detailsBillableItemsService;

    public function __construct($id = null)
    {
        $this->detailsBillableItemsService = DetailsBillableItemsService::getInstance();
        parent::__construct($id);
    }

    public function mount(BillableItem $billable_item)
    {
        $this->detailsBillableItemsService->mount($this, $billable_item);
    }

    public function render()
    {
        return view('livewire.v1.admin.invoicing.billableItems.details-billable-items')->extends('layouts.v1.app');
    }

}
