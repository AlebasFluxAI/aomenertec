<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Tax;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Invoicing\BillableItems\AddBillableItemsService;
use App\Http\Services\V1\Admin\Invoicing\BillableItems\IndexBillableItemsService;
use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Tax\AddTaxService;
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

class TaxAddComponent extends Component
{
    public $name;
    public $description;
    public $percentage;


    private $addTaxService;

    public function __construct($id = null)
    {
        $this->addTaxService = AddTaxService::getInstance();
        parent::__construct($id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.invoicing.tax.add-tax')->extends('layouts.v1.app');
    }

    public function submitForm()
    {
        return $this->addTaxService->submitForm($this);
    }
}
