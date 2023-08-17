<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Http\Services\Singleton;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientTechnician;
use App\Models\V1\EquipmentClient;
use App\Models\V1\ClientType;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\Municipality;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Client;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class ClientHandReadingDetailService extends Singleton
{

    public function mount(Component $component, MicrocontrollerData $model)
    {
        $json= json_decode($model->raw_json);
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
