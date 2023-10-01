<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Invoice;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Invoicing\BillableItems\DetailsBillableItemsService;
use App\Http\Services\V1\Admin\Invoicing\BillableItems\IndexBillableItemsService;
use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Invoice\DetailsInvoiceService;
use App\Http\Services\V1\Admin\Invoicing\Invoice\InvoiceDetailsGuestClientService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\BillableItem;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use App\Models\V1\Invoice;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;

class InvoiceDetailsGuestComponent extends Component
{
    private $detailsInvoiceService;
    public $model;
    public $data = [];


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
