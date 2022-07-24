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
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\ClientRecharge;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use App\Models\V1\Pqr;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;
use App\Models\V1\Client;

class PurchaseGuestCreateService extends Singleton
{


    public function mount(Component $component)
    {
        $component->fill([
            "purchase_types" => $this->getPurchaseType(),
            "total" => 0,
            "purchase_type" => PurchaseGuestCreateComponent::PURCHASE_TYPE_CASH,
        ]);
    }

    private function getPurchaseType()
    {
        return [
            [
                "key" => "Recarga por dinero",
                "value" => PurchaseGuestCreateComponent::PURCHASE_TYPE_CASH,
            ],
            [
                "key" => "Recarga por Kwh",
                "value" => PurchaseGuestCreateComponent::PURCHASE_TYPE_KWH,
            ],
        ];
    }

    public function submitForm(Component $component)
    {
        $client_code = $component->client_code;
        $client_identification = $component->client_identification;

        if (!$client_code and !$client_identification) {
            $component->addError('blank_client', "Debes ingresar tu codigo o identificación");
        }

        $client = Client::whereIdentification($client_identification)->first();
        if (!$client) {
            $client = Client::whereCode($client_code)->first();
        }
        if (!$client) {
            $component->addError('blank_client', "No se existe un cliente con los datos registrados");
            return;
        }

        $networkOperator = $client->networkOperator;
        if (!$networkOperator) {
            $component->addError('blank_client', "No se encuetran tarifas contacta con soporte");
            return;
        }

        $component->price = $networkOperator->photovoltaicPrice()
            ->whereStratumId($client->stratum_id)
            ->first();


    }

}
