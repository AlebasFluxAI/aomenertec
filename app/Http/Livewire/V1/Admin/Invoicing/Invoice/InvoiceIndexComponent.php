<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Invoice;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Invoicing\BillableItems\IndexBillableItemsService;
use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Invoice\IndexInvoiceService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
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

    public function setFilter($filterValue)
    {
        return $this->indexInvoicetemsService->setFilter($this, $filterValue);
    }


    public function getData()
    {
        return $this->indexInvoicetemsService->getData($this);
    }
}
