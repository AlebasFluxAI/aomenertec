<?php

namespace App\Http\Services\V1\Admin\User\Purchase;

use App\Events\ChatEvent;
use App\Http\Livewire\V1\Admin\Purchase\PurchaseGuestCreateComponent;
use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
use App\Models\Traits\CreateRechargeTrait;
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\Client;
use App\Models\V1\ClientRecharge;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use App\Models\V1\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;

class PurchaseCreateService extends Singleton
{
    use CreateRechargeTrait;


    public function confirmRecharge(Component $component)
    {
        DB::transaction(function () use ($component) {
            ClientRecharge::create([
                "client_id" => $component->client->id,
                "network_operator_id" => $component->networkOperator->id,
                "seller_id" => User::getUserModel()->id,
                "kwh_price" => $component->price->price,
                "kwh_credit" => $component->price->credit,
                "kwh_subsidy" => $component->price->subsidy,
                "kwh_quantity" => $component->kwh_quantity,
                "total" => $component->total,
                "reference" => $component->reference,
                "status" => ClientRecharge::PURCHASE_PAYMENT_STATUS_PENDING,
            ]);
        });

    }


}
