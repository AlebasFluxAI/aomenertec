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
use Crc16\Crc16;
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
        $component->recharge_code =
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


    public function Generar(Component $component, $key, $cantidad, $cons){
        $kw = $this->byteArray($component->kwh_quantity*100);
        $consecutivo = $this->byteArray1($cons);
        $crcin = [$consecutivo[1],$consecutivo[0],$kw[3],$kw[2],$kw[1],$kw[0]];
        $crc = Crc16::XMODEM(implode("",$crcin));
        $aux = dechex($crc);
        $crc4 = str_pad($aux, 4, "0", STR_PAD_LEFT);
        $intcrc1 = hexdec((str_split($crc4, 2)[0]));
        $intcrc2 = hexdec((str_split($crc4, 2)[1]));
        $crckey = Crc16::XMODEM($key.$crc4);
        $aux2 = dechex($crckey);
        $crckey4 = str_pad($aux2, 4, "0", STR_PAD_LEFT);
        $intcrck1 = hexdec((str_split($crckey4, 2)[0]));
        $intcrck2 = hexdec((str_split($crckey4, 2)[1]));
        array_push($crcin,  $intcrc1, $intcrc2, $intcrck1, $intcrck2);
        for ($index = 0; $index<10; $index++){
            $hex = dechex($crcin[$index]);
            $crcin[$index] = str_pad($hex, 2, "0", STR_PAD_LEFT);
        }
        $encrypt = implode("",$crcin);
        $f=str_replace("f", "#", $encrypt);
        $e=str_replace("e", "*", $f);
        return strtoupper($e);
    }
    public function byteArray($val){
        $byteArr =[0,0,0,0];
        for ($index = 0; $index < 4; $index++) {
            $byte = $val & 0xff;
            $byteArr[$index] = $byte;
            $val = ($val - $byte) / 256 ;
        }
        return $byteArr;
    }
    public function byteArray1($val){
        $byteArr1 =[0,0];
        for ($index = 0; $index < 2; $index++) {
            $byte = $val & 0xff;
            $byteArr1[$index] = $byte;
            $val = ($val - $byte) / 256 ;
        }
        return $byteArr1;
    }
}
