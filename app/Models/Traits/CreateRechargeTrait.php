<?php

namespace App\Models\Traits;

use App\Http\Livewire\V1\Admin\Purchase\PurchaseGuestCreateComponent;
use App\Models\V1\Client;
use Crc16\Crc16;
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
    public $recharge_code;

    public function updatedPurchaseType(Component $component)
    {
        $component->kwh_quantity = 0;
        $component->total = 0;
    }

    public function updatedKwhQuantity(Component $component)
    {
        if ($component->purchase_type == "kwh") {
            if ($component->kwh_quantity != "") {
                $component->total = $component->price->price * $component->kwh_quantity;
            } else {
                $component->kwh_quantity = 0;
                $component->total = 0;
            }
        } else {
            $component->total = $component->kwh_quantity / $component->price->price;
        }
    }

    public function updatedTotal(Component $component)
    {
        if ($component->purchase_type == "kwh") {
            $component->total = $component->price->price * $component->kwh_quantity;
        } else {
            if ($component->total != "") {

                $component->kwh_quantity = $component->total / $component->price->price;
            } else {
                $component->total = 0;
                $component->kwh_quantity = 0;
            }
        }
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
        if (!$component->price) {
            $component->addError('blank_client', "No se encuetran tarifas contacta con soporte");
            return;
        }
    }


    public function mount(Component $component)
    {
        $component->fill([
            "recharge_code" => null,
            "reference" => strval(Str::uuid()),
            "purchase_types" => $this->getPurchaseType(),
            "total" => 0,
            "kwh_quantity" => 0,
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

    public function createRechargeCode(Component $component)
    {
        $key = ($component->client->equipments()->whereEquipmentTypeId(1)->first())->serial . $component->client->networkOperator->identification;
        $cons = $component->client->lastConsecutiveRecharge() + 1;
        $kw = $this->byteArray($component->kwh_quantity * 100);
        $consecutivo = $this->byteArray1($cons);
        $crcin = [$consecutivo[1], $consecutivo[0], $kw[3], $kw[2], $kw[1], $kw[0]];
        $crc = Crc16::XMODEM(implode("", $crcin));
        $aux = dechex($crc);
        $crc4 = str_pad($aux, 4, "0", STR_PAD_LEFT);
        $intcrc1 = hexdec((str_split($crc4, 2)[0]));
        $intcrc2 = hexdec((str_split($crc4, 2)[1]));
        $crckey = Crc16::XMODEM($key . $crc4);
        $aux2 = dechex($crckey);
        $crckey4 = str_pad($aux2, 4, "0", STR_PAD_LEFT);
        $intcrck1 = hexdec((str_split($crckey4, 2)[0]));
        $intcrck2 = hexdec((str_split($crckey4, 2)[1]));
        array_push($crcin, $intcrc1, $intcrc2, $intcrck1, $intcrck2);
        for ($index = 0; $index < 10; $index++) {
            $hex = dechex($crcin[$index]);
            $crcin[$index] = str_pad($hex, 2, "0", STR_PAD_LEFT);
        }
        $encrypt = implode("", $crcin);
        $f = str_replace("f", "#", $encrypt);
        $e = str_replace("e", "*", $f);
        return strtoupper($e);
    }

    public function byteArray($val)
    {
        $byteArr = [0, 0, 0, 0];
        for ($index = 0; $index < 4; $index++) {
            $byte = $val & 0xff;
            $byteArr[$index] = $byte;
            $val = ($val - $byte) / 256;
        }
        return $byteArr;
    }

    public function byteArray1($val)
    {
        $byteArr1 = [0, 0];
        for ($index = 0; $index < 2; $index++) {
            $byte = $val & 0xff;
            $byteArr1[$index] = $byte;
            $val = ($val - $byte) / 256;
        }
        return $byteArr1;
    }
}
