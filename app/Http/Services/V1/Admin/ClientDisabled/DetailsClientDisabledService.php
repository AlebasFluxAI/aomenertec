<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\AddClient;
use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\EquipmentClient;
use App\Models\V1\ClientType;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\Municipality;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Client;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class DetailsClientDisabledService extends Singleton
{
    public function mount(Component $component, Client $model)
    {
        $component->fill([
            'client' => $model,
        ]);
        foreach ($model->equipments as $index => $item) {
            $component->equipment[$index] = ["key" => $item->equipmentType->type, "value" => $item->serial . " - " . $item->description];
        }
    }


}
