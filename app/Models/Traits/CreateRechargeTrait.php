<?php

namespace App\Models\Traits;

use App\Http\Livewire\V1\Admin\Purchase\PurchaseGuestCreateComponent;
use App\Models\V1\Client;
use App\Models\V1\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Livewire\Component;

trait CreateRechargeTrait
{

    public $client_code;
    public $client_identification;
    public $purchase_types;
    public $kwh_quantity;
    public $purchase_type;
    public $total;
    public $price;
    public $client;
    public $networkOperator;
    public $reference;

    public function updatedPurchaseType(Component $component)
    {
        $component->kwh_quantityal = 0;
        $component->total = 0;
    }

    public function updatedKwhQuantity(Component $component)
    {
        $component->total = $component->price->price * $component->kwh_quantity;
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
        $component->networkOperator = $networkOperator;
        $component->client = $client;
        $component->price = $networkOperator->photovoltaicPrice()
            ->whereStratumId($client->stratum_id)
            ->first();
    }


    public function mount(Component $component)
    {
        $component->fill([
            "reference" => strval(Str::uuid()),
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
}
