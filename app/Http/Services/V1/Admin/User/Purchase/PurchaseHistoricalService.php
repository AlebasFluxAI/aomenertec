<?php

namespace App\Http\Services\V1\Admin\User\Purchase;

use App\Events\ChatEvent;
use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
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

class PurchaseHistoricalService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->model = $model;
    }

    public function getData(Component $component)
    {
        return $component->model->clientRecharges()->paginate(15);
    }
}
