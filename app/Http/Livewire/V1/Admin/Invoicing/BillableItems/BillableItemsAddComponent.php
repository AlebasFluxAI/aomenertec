<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\BillableItems;

use App\Http\Services\V1\Admin\Invoicing\BillableItems\AddBillableItemsService;
use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use Livewire\Component;
use function view;

class BillableItemsAddComponent extends Component
{
    public $name;
    public $description;
    public $taxes;
    public $tax_id;


    private $addBillableItemService;

    public function __construct($id = null)
    {
        $this->addBillableItemService = AddBillableItemsService::getInstance();
        parent::__construct($id);
    }

    public function mount()
    {
        $this->addBillableItemService->mount($this);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.invoicing.billableItems.add-billable-items')->extends('layouts.v1.app');
    }

    public function submitForm()
    {
        return $this->addBillableItemService->submitForm($this);
    }
}
