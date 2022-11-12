<?php

namespace App\Http\Services\V1\Admin\Equipment;

use App\Http\Livewire\V1\Admin\Equipment\AddEquipment;
use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Scope\PaginationScope;
use Livewire\Component;

class EquipmentIndexService extends Singleton
{
    public function getEquipments()
    {
        return Equipment::with("equipmentType")->pagination();
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
        $component->reset();
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

    public function getPermission()
    {
        $model = User::getUserModel();
        if ($model::class == NetworkOperator::class) {
            return [\App\Http\Resources\V1\Permissions::TECHNICIAN_REMOVE_EQUIPMENT];
        } elseif ($model::class == Admin::class) {
            return [\App\Http\Resources\V1\Permissions::NETWORK_OPERATOR_REMOVE_EQUIPMENT];
        } elseif ($model::class == SuperAdmin::class) {
            return [\App\Http\Resources\V1\Permissions::ADMIN_REMOVE_EQUIPMENT];
        }
        return [\App\Http\Resources\V1\Permissions::TECHNICIAN_REMOVE_EQUIPMENT];
    }

    public function getFunctionRemoveEquipment()
    {
        $model = User::getUserModel();
        if ($model::class == NetworkOperator::class) {
            return "removeEquipmentTechnician";
        } elseif ($model::class == Admin::class) {
            return "removeEquipmentAdmin";
        } elseif ($model::class == SuperAdmin::class) {
            return "removeEquipmentAdmin";
        }
        return "removeEquipmentTechnician";
    }

    public function getConditionalRemoveEquipment()
    {
        $model = User::getUserModel();
        if ($model::class == NetworkOperator::class) {
            return "conditionalRemoveEquipmentTechnician";
        } elseif ($model::class == Admin::class) {
            return "conditionalRemoveEquipmentNetworkoperator";
        } elseif ($model::class == SuperAdmin::class) {
            return "conditionalRemoveEquipmentAdmin";
        }
        return "conditionalRemoveEquipmentTechnician";
    }

    public function getData(Component $component)
    {
        $model = User::getUserModel();
        if ($component->filter) {
            if ($model::class == NetworkOperator::class) {
                return Equipment::whereNetworkOperatorId($model->id)
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%')
                    ->pagination();
            } elseif ($model::class == Admin::class) {
                return Equipment::where($component->filterCol, 'ilike', '%' . $component->filter . '%')
                    ->pagination();
            } elseif ($model::class == Technician::class) {
                return Equipment::where($component->filterCol, 'ilike', '%' . $component->filter . '%')
                    ->pagination();
            }
            return Equipment::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }

        if ($model::class == NetworkOperator::class) {
            return Equipment::whereNetworkOperatorId($model->id)
                ->pagination();
        } elseif ($model::class == Admin::class) {
            return Equipment::whereAdminId($model->id)
                ->pagination();
        } elseif ($model::class == Technician::class) {
            return Equipment::whereTechnicianId($model->id)
                ->pagination();
        }
        return Equipment::pagination();
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
