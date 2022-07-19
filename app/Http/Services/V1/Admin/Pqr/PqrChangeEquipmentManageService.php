<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Resources\V1\Menu;
use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\Traits\PqrStatusTrait;
use App\Models\V1\Admin;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentClient;
use App\Models\V1\EquipmentType;
use App\Models\V1\HistoricalClientEquipment;
use App\Models\V1\Pqr;
use App\Models\V1\PqrUser;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Technician;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PqrChangeEquipmentManageService extends Singleton
{
    use PqrStatusTrait;

    public function mount(Component $component, $model)
    {
        $component->model = $model;
        $component->equipmentToChange = [];
    }

    public function updatedSelectedRows(Component $component)
    {
        $component->equipmentToChange = Equipment::whereIn("id", $component->selectedRows)
            ->where("status", "!=", Equipment::STATUS_REPAIR_PENDING)
            ->get();

    }

    public function equipmentByType(Component $component, $equipmentType)
    {
        return Equipment::whereEquipmentTypeId($equipmentType)
            ->where("status", "!=", Equipment::STATUS_REPAIR_PENDING)
            ->get();
    }

    public function confirmEquipmentChange(Component $component, $equipmentId)
    {
        DB::transaction(function () use ($component, $equipmentId) {

            $equipment = Equipment::find($equipmentId);
            $equipmentSelectedToChange = $component->equipmentToChange
                ->where("equipment_type_id", $equipment->equipment_type_id)
                ->first()
                ->id;
            $client = $component->model->client;

            EquipmentClient::whereClientId($client->id)
                ->whereEquipmentId($equipmentSelectedToChange)
                ->delete();

            Equipment::find($equipmentSelectedToChange)->update([
                "status" => Equipment::STATUS_REPAIR_PENDING
            ]);

            if (!EquipmentClient::whereClientId($client->id)
                ->whereEquipmentId($equipmentSelectedToChange)
                ->whereCurrentAssigned(true)
                ->exists()) {

                EquipmentClient::create([
                    'client_id' => $client->id,
                    'equipment_id' => $equipmentId,
                    'current_assigned' => true,
                ]);

                HistoricalClientEquipment::create([
                    "client_id" => $client->id,
                    "before_equipment_id" => $equipmentSelectedToChange,
                    "equipment_id" => $equipmentId,
                    "pqr_id" => $component->model->id,
                    "assigned_by_id" => Menu::getUserModel()->id,
                    "assigned_by_model" => Menu::getUserModel()::class,
                ]);
            }


            $component->equipmentToChange = $component->equipmentToChange->reject(function ($element) use ($equipmentSelectedToChange) {
                return $element->id == $equipmentSelectedToChange;
            });
            $component->selectedRows = [];
            $component->model->setEquipmentChanged();
            if (!$component->equipmentToChange->count()) {
                $component->redirectRoute("administrar.v1.peticiones.cambio-equipo-historico"
                    , ["pqr" => $component->model->id]);
            } else {
                $component->emitTo('livewire-toast', 'show',
                    ['type' => 'success',
                        'message' => "Se realizo el cambio del equipo"
                    ]);
            }
        });

    }

}
