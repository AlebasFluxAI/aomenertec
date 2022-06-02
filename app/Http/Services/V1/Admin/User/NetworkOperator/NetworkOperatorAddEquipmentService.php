<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Services\Singleton;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperatorEquipmentType;
use App\Models\V1\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NetworkOperatorAddEquipmentService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->model = $model;
        $component->fill([
            "equipments" => $this->getEquipments(),
            "equipmentRelated" => $model->equipments
        ]);
    }

    private function getEquipments()
    {
        if ($admin = User::getUserModel()) {
            return $admin->adminEquipmentToNetworkOperatorsAsKeyValue();
        }
        return [];
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            if (!$component->equipmentId) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Seleccione un tipo de equipo"]);
                return;
            }
            if ($component->model->equipments()->whereId($component->equipmentId)->exists()) {
                $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Este tipo ya fue asignado"]);

                return;
            }
            Equipment::whereId($component->equipmentId)
                ->update([
                    "network_operator_id" => $component->model->id
                ]);

            $this->refreshAdminEquipmentType($component);
            $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Tipo de equipo agregado"]);
        });
    }

    private function refreshAdminEquipmentType($component)
    {
        $component->equipmentRelated = $component->model->equipments()->get();
        $component->equipments = $this->getEquipments();
    }

    public function delete(Component $component, $equipmentId)
    {
        Equipment::whereId($equipmentId)
            ->update([
                "network_operator_id" => null
            ]);

        $this->refreshAdminEquipmentType($component);
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Tipo de equipo eliminado"]);
    }

    public function assignType(Component $component)
    {
    }
}
