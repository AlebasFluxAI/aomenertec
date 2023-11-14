<?php

namespace App\Http\Services\V1\Admin\User\Technician;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Livewire\Component;

class TechnicianDetailsService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }

    public function conditionalDeleteEquipment(Component $component, $id)
    {
        $model = User::getUserModel();
        if ($model::class == SuperAdmin::class) {
            return Equipment::find($id)->has_admin;
        } elseif ($model::class == Admin::class) {
            return Equipment::find($id)->has_network_operator;
        }
        return false;
    }

    public function deleteEquipment(Component $component, $equipmentId)
    {
        Equipment::find($equipmentId)->delete();
        $component->emitTo('livewire-toast', 'show', "Equipo {$equipmentId} eliminado exitosamente");
        $component->reset();
    }

    public function delete(Component $component, $clientId)
    {
        Client::find($clientId)->delete();
        $component->emitTo('livewire-toast', 'show', "Equipo {$clientId} eliminado exitosamente");
        $component->reset();
    }

    public function conditionalRemoveEquipmentTechnician(Component $component, $id)
    {
        if (Equipment::find($id)->has_clients) {
            return Equipment::find($id)->has_clients;
        } else {
            return !Equipment::find($id)->has_technician;
        }
    }

    public function removeEquipmentTechnician(Component $component, $id)
    {
        $model = User::getUserModel();
        $equipment = Equipment::find($id);
        $equipment->has_technician = false;
        $equipment->technician_id = null;
        $equipment->save();
        $component->emitTo('livewire-toast', 'show', "Equipo {$id} removido exitosamente de {$model->name}");
    }

    public function conditionalMonitoring(Component $component, $modelId)
    {
        return !MicrocontrollerData::whereClientId($modelId)->exists();
    }

    public function conditionalDeleteClient(Component $component, $modelId)
    {
        return MicrocontrollerData::whereClientId($modelId)->exists();
    }


    public function conditionalEquipmentRepaired($id)
    {
        $equipment = Equipment::find($id);
        return !($equipment->status == Equipment::STATUS_REPAIR_PENDING or $equipment->status == Equipment::STATUS_REPAIR);
    }

    public function repairEquipment($id)
    {
        $equipment = Equipment::find($id);
        $equipment->repair();
    }

    public function conditionalEquipmentDeprecate($id)
    {
        $equipment = Equipment::find($id);
        return !$equipment->canDeprecate();
    }

    public function deprecateEquipment($id)
    {
        $equipment = Equipment::find($id);
        $equipment->deprecate();
    }
}
