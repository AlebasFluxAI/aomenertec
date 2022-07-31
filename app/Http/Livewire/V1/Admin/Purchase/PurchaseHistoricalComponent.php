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
use App\Http\Services\V1\Admin\User\Purchase\PurchaseHistoricalService;
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use App\Models\V1\Seller;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;

class PurchaseHistoricalComponent extends Component
{
    use WithPagination;

    private $purchaseHistoricalService;
    public $model;

    public function __construct($id = null)
    {
        $this->purchaseHistoricalService = PurchaseHistoricalService::getInstance();
        parent::__construct($id);
    }

    public function mount(Seller $seller)
    {
        $this->purchaseHistoricalService->mount($this, $seller);
    }

    public function getData()
    {
        return $this->purchaseHistoricalService->getData($this);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.purchase.historical-purchase',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }
}
