<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Livewire\Component;

class AdminDetailsService extends Singleton
{
    public function mount(Component $component, Admin $admin)
    {
        $component->fill([
            'admin' => $admin,
            'clients' => Client::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))->get(),
        ]);
    }

    public function conditionalDeleteNetworkOperator(Component $component, $modelId)
    {
        return Client::whereNetworkOperatorId($modelId)->exists();
    }

    public function deleteNetworkOperator(Component $component, $networkOperatorId)
    {
        $operator = NetworkOperator::find($networkOperatorId);
        $operator->user->enabled = false;
        $operator->push();
        foreach ($operator->equipments()->get() as $type) {
            $type->network_operator_id = null;
            $type->save();
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$operator->name} eliminado"]);
        $operator->delete();
    }

    public function delete(Component $component, $clientId)
    {
        Client::find($clientId)->delete();
        $component->emitTo('livewire-toast', 'show', "Equipo {$clientId} eliminado exitosamente");
        $component->reset();
    }

    public function disableNetworkOperator(Component $component, $modelId)
    {
        $operator = NetworkOperator::find($modelId);
        $operator->enabled = !$operator->enabled;
        $operator->user->enabled = !$operator->user->enabled;
        $operator->push();
        if (!$operator->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function getEnabledNetworkOperator(Component $component, $modelId)
    {
        return !NetworkOperator::find($modelId)->enabled;
    }

    public function getEnabledAuxNetworkOperator(Component $component, $modelId)
    {
        if (!NetworkOperator::find($modelId)->enabled) {
            return false;
        }
        return true;
    }

    public function conditionalLinkEquipmentNetworkOperator(Component $component, $modelId)
    {
        return !NetworkOperator::find($modelId)->admin->equipments()->exists();
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
