<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\BillableItems;

use App\Http\Services\V1\Admin\Invoicing\BillableItems\EditBillableItemsService;
use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Models\V1\BillableItem;
use Livewire\Component;
use function view;

class BillableItemsEditComponent extends Component
{
    public $model;
    public $name;
    public $description;
    public $taxes;
    public $tax_id;
    private $editBillableItemsService;

    public function __construct($id = null)
    {
        $this->editBillableItemsService = EditBillableItemsService::getInstance();
        parent::__construct($id);
    }

    public function mount(BillableItem $billable_item)
    {
        $this->editBillableItemsService->mount($this, $billable_item);
    }

    public function render()
    {
        return view('livewire.v1.admin.invoicing.billableItems.edit-billable-items')->extends('layouts.v1.app');
    }

    public function submitForm()
    {
        return $this->editBillableItemsService->submitForm($this);
    }


}
