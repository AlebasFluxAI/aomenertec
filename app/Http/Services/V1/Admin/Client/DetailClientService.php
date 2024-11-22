<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class DetailClientService extends Singleton
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

    public function conditionalDeleteTechnician(Component $component, $modelId)
    {
        return Technician::find($modelId)->clientTechnicians()->exists();
    }

    public function deleteTechnician(Component $component, $technicianId)
    {
        $technician = Technician::find($technicianId);
        $technician->user->enabled = false;
        $technician->push();
        foreach ($technician->equipments()->get() as $type) {
            $type->technician_id = "";
            $type->save();
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$technician->name} eliminado"]);
        $technician->delete();
    }

    public function disableTechnician(Component $component, $modelId)
    {
        $technician = Technician::find($modelId);
        $technician->enabled = !$technician->enabled;
        $technician->user->enabled = !$technician->user->enabled;
        $technician->push();
        if (!$technician->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function getEnabledAuxTechnician(Component $component, $modelId)
    {
        if (!Technician::find($modelId)->enabled) {
            return false;
        }
        return true;
    }

    public function getEnabledTechnician(Component $component, $modelId)
    {
        return !Technician::find($modelId)->enabled;
    }

    public function conditionalLinkEquipmentTechnician(Component $component, $modelId)
    {
        return !Technician::find($modelId)->networkOperator->equipments()->exists();
    }

    public function conditionalLinkClientsTechnician(Component $component, $modelId)
    {
        return !Technician::find($modelId)->networkOperator->clients()->exists();
    }

    public function conditionalDeleteSupervisor(Component $component, $modelId)
    {
        return Supervisor::find($modelId)->clientSupervisors()->exists();
    }

    public function deleteSupervisor(Component $component, $supervisorId)
    {
        $supervisor = Supervisor::find($supervisorId);
        $supervisor->user->enabled = false;
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$supervisor->name} eliminado"]);
        $supervisor->delete();
    }

    public function disableSupervisor(Component $component, $modelId)
    {
        $supervisor = Supervisor::find($modelId);
        $supervisor->enabled = !$supervisor->enabled;
        $supervisor->user->enabled = !$supervisor->user->enabled;
        $supervisor->push();
        if (!$supervisor->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function conditionalLinkClientsSupervisor(Component $component, $modelId)
    {
        return !Supervisor::find($modelId)->networkOperator->clients()->exists();
    }

    public function getEnabledSupervisor(Component $component, $modelId)
    {
        return !Supervisor::find($modelId)->enabled;
    }

    public function getEnabledAuxSupervisor(Component $component, $modelId)
    {
        if (!Supervisor::find($modelId)->enabled) {
            return false;
        }
        return true;
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
        //$component->reset();
        $component->client = Client::find($component->client->id);
    }

    public function conditionalRemoveEquipmentAdmin(Component $component, $id)
    {
        if (Equipment::find($id)->has_clients) {
            return Equipment::find($id)->has_clients;
        } else {
            return !Equipment::find($id)->has_admin;
        }
    }

    public function removeEquipmentAdmin(Component $component, $id)
    {
        $model = User::getUserModel();
        $equipment = Equipment::find($id);
        $equipment->has_technician = false;
        $equipment->technician_id = null;
        $equipment->has_network_operator = false;
        $equipment->network_operator_id = null;
        $equipment->has_admin = false;
        $equipment->admin_id = null;
        $equipment->save();
        $component->emitTo('livewire-toast', 'show', "Equipo {$id} removido exitosamente de {$model->name}");
        $component->reset();
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

    public function disableClient(Component $component, $clientId)
    {
        DB::transaction(function () use ($clientId, $component) {
            Client::find($clientId)->disableClient();
            ToastEvent::launchToast($component, "show", "success", "Cliente desactivado exitosamente");
        });
        $component->reset();
    }
}
