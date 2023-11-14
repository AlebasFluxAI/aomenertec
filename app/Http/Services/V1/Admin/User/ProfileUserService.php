<?php

namespace App\Http\Services\V1\Admin\User;

use App\Http\Resources\V1\Menu;
use App\Http\Services\Singleton;
use App\Models\Traits\NetworkOperatorPriceTrait;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\Consumer;
use App\Models\V1\Equipment;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileUserService extends Singleton
{
    use NetworkOperatorPriceTrait;

    public function mount(Component $component)
    {
        $component->model = $this->getModelByUser();
        if (Auth::user()->hasRole(User::TYPE_SUPER_ADMIN)) {
            $component->admins = Admin::get();
            $component->network_operators = NetworkOperator::get();
            $component->equipment = Equipment::get();
        }
        $component->supervisors = [];
    }

    private function getModelByUser()
    {
        return Menu::getUserModel();
    }

    public function getViewName()
    {
        return Menu::getHome();
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
        $component->reset();
    }

    public function conditionalMonitoring($clientId)
    {
        return !MicrocontrollerData::whereClientId($clientId)->exists();
    }

    public function blinkSupportPqrAvailability($supportId)
    {
        return Support::find($supportId)->blinkPqrAvailability();
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

}
