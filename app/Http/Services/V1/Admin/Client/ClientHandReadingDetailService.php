<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Livewire\Component;

class ClientHandReadingDetailService extends Singleton
{

    public function mount(Component $component, MicrocontrollerData $model)
    {
        $json = json_decode($model->raw_json);
        $client = $model->client;
        if (!$client) {
            $equipment_serial = str_pad($json->equipment_id, 6, "0", STR_PAD_LEFT);
            $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                ->first();
            if ($equipment) {
                $client = $equipment->clients()->first();
            }
        }
        $component->fill([
            'model' => $model,
            'client' => $client
        ]);
    }


}
