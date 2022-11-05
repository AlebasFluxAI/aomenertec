<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Livewire\Component;

class SuperAdminDetailsService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
            'network_operators' => NetworkOperator::get(),
            'admins' => Admin::get(),
            'equipment' => Equipment::get()
        ]);
    }

    public function conditionalDeleteAdmin(Component $component, $modelId)
    {
        return NetworkOperator::whereAdminId($modelId)->exists();
    }

    public function deleteAdmin(Component $component, $modelId)
    {
        $admin = Admin::find($modelId);
        $admin->user->enabled = false;
        $admin->push();
        foreach ($admin->adminClientTypes()->get() as $type) {
            $type->delete();
        }
        foreach ($admin->priceAdmin()->get() as $type) {
            $type->delete();
        }
        foreach ($admin->adminEquipmentTypes()->get() as $type) {
            $type->delete();
        }
        foreach ($admin->equipments()->get() as $type) {
            $type->admin_id = "";
            $type->save();
        }
        if ($admin->configAdmin()->exists()) {
            $admin->configAdmin()->delete();
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$admin->name} eliminado"]);
        $admin->delete();
    }

    public function disableAdmin(Component $component, $modelId)
    {
        $admin = Admin::find($modelId);
        $admin->enabled = !$admin->enabled;
        $admin->user->enabled = !$admin->user->enabled;
        $admin->push();
        if (!$admin->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function getEnabledAdmin(Component $component, $modelId)
    {
        return !Admin::find($modelId)->enabled;
    }

    public function getEnabledAuxAdmin(Component $component, $modelId)
    {
        if (!Admin::find($modelId)->enabled) {
            return false;
        }
        return true;
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

    public function conditionalDeleteTechnician(Component $component, $modelId)
    {
        return Technician::find($modelId)->clientTechnicians()->exists();
    }

    public function conditionalRemoveEquipmentNetworkOperator(Component $component, $id)
    {
        if (Equipment::find($id)->has_clients) {
            return Equipment::find($id)->has_clients;
        } else {
            return !Equipment::find($id)->has_network_operator;
        }
    }

    public function removeEquipmentNetworkOperator(Component $component, $id)
    {
        $model = User::getUserModel();
        $equipment = Equipment::find($id);
        $equipment->has_technician = false;
        $equipment->technician_id = null;
        $equipment->has_network_operator = false;
        $equipment->network_operator_id = null;
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
}
