<?php

namespace App\Http\Livewire\V1\Admin\Purchase;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
use App\Http\Services\V1\Admin\User\Purchase\PurchaseGuestCreateService;
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;
use NumberFormatter;

class PurchaseGuestCreateComponent extends Component
{

    private $purchaseGuestCreateService;
    public $client_code;
    public $client_identification;
    public $purchase_types;
    public $kwh_quantity;
    public $purchase_type;
    public $total;
    public $price;

    public const PURCHASE_TYPE_KWH = "kwh";
    public const PURCHASE_TYPE_CASH = "cash";

    public function __construct($id = null)
    {
        $this->purchaseGuestCreateService = PurchaseGuestCreateService::getInstance();
        parent::__construct($id);
    }


    public function mount()
    {


        $this->purchaseGuestCreateService->mount($this);
    }

    public function submitForm()
    {
        $this->purchaseGuestCreateService->submitForm($this);

    }

    public function render()
    {
        return view(
            'livewire.v1.admin.purchase.guest-create-purchase'
        )->extends('layouts.v1.app');
    }


}
