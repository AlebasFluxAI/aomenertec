<?php

namespace App\Http\Livewire\V1\Admin\Purchase;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
use App\Http\Services\V1\Admin\User\Purchase\PurchaseCreateService;
use App\Models\Traits\CreateRechargeTrait;
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;

class PurchaseCreateComponent extends Component
{
    use CreateRechargeTrait;

    private $purchaseCreateService;


    public function __construct($id = null)
    {
        $this->purchaseCreateService = PurchaseCreateService::getInstance();
        parent::__construct($id);
    }


    public function mount()
    {
        $this->purchaseCreateService->mount($this);
    }

    public function updatedPurchaseType()
    {
        $this->purchaseCreateService->updatedPurchaseType($this);
    }

    public function updatedKwhQuantity()
    {
        $this->purchaseCreateService->updatedKwhQuantity($this);
    }

    public function confirmRecharge()
    {
        $this->purchaseCreateService->confirmRecharge($this);
    }

    public function submitForm()
    {
        $this->purchaseCreateService->submitForm($this);
    }


    public function render()
    {
        return view(
            'livewire.v1.admin.purchase.create-purchase'
        )->extends('layouts.v1.app');
    }
}
