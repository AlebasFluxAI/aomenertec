<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Livewire\V1\Admin\Client\AddClient;
use App\Http\Services\Singleton;
use App\Models\V1\ClientTechnician;
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
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class AddClientTechnicianService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            "model" => $model,
            "technicians" => ($model->technician->count() != 0 ? [] : $this->getTechnicians()),
            "technician_related" => $model->technician
        ]);

    }

    private function getTechnicians()
    {
        if ($networkOperator = User::getUserModel() and method_exists($networkOperator, "networkOperatorTechniciansAsKeyValue")) {
            return $networkOperator->networkOperatorTechniciansAsKeyValue();
        }
        return [];
    }

    public function relateTechnician(Component $component)
    {
        DB::transaction(function () use ($component) {
            ClientTechnician::create([
                "client_id" => $component->model->id,
                "technician_id" => $component->technicianId,
            ]);
            $component->emitTo('livewire-toast', 'show', "Tecnico relacionado exitosamente");
            $this->refreshTechnician($component);
        });
    }

    private function refreshTechnician(Component $component)
    {
        $component->technician_related = $component->model->technician()->get();
        $component->technicians = $component->technician_related->count() != 0 ? [] : $this->getTechnicians();
    }

    public function delete(Component $component, $technicianId)
    {
        DB::transaction(function () use ($component, $technicianId) {
            ClientTechnician::whereTechnicianId($technicianId)->whereClientId($component->model->id)->delete();
            $component->emitTo('livewire-toast', 'show', "Tecnico removido exitosamente");
            $this->refreshTechnician($component);
        });
    }

}

