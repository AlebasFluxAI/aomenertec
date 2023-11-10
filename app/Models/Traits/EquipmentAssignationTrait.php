<?php

namespace App\Models\Traits;

use App\Models\V1\Admin;
use App\Models\V1\Equipment;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Technician;
use Livewire\Component;

trait EquipmentAssignationTrait
{
    public $equipmentTypes = [];
    public $equipmentTypeId;
    public $filterCol;
    public $equipment;
    public $equipments;
    public $equipment_id;
    public $equipmentId;
    public $equipmentBachelors = [];
    public $equipmentFilter;

    public function updatedSelectedAll(Component $component)
    {
        if (!$component->selectedAll) {
            $component->selectedRows = [];
        } else {
            $component->selectedRows = is_array($component->equipmentBachelors) ? [] : $component->equipmentBachelors->pluck("id");
        }
    }


    public function updatedEquipmentTypeId(Component $component)
    {
        $this->setEquipmentBachelors($component);
    }

    private function setEquipmentBachelors(Component $component)
    {
        if (!$component->equipmentTypeId) {
            return;
        }
        $component->equipmentBachelors = (match ($component->assignationType) {
            Admin::class => $this->getEquipmentBachelors($component)->whereNull("admin_id"),
            NetworkOperator::class => $this->getEquipmentBachelors($component)
                ->whereHasNetworkOperator(false)
                ->whereIn(
                    "id",
                    $component
                        ->model
                        ->admin
                        ->equipments
                        ->pluck("id")
                ),
            Technician::class => $this->getEquipmentBachelors($component)->whereNull("technician_id")
                ->whereIn(
                    "id",
                    $component
                        ->model
                        ->networkOperator
                        ->equipments
                        ->pluck("id")
                ),
        })->get();
    }

    private function getEquipmentBachelors(Component $component)
    {
        return Equipment::whereEquipmentTypeId($component->equipmentTypeId)
            ->where(function ($query) use ($component) {
                if ($component->equipmentFilter) {
                    return $query->
                    where("serial", "like", '%' . $component->equipmentFilter . "%")
                        ->orWhere("name", "like", '%' . $component->equipmentFilter . "%");
                }
            })
            ->whereAssigned(false)
            ->whereNotIn("id", $component->model->refresh()->equipments->pluck("id"))
            ->orderBy("id", "desc");
    }

    public function updatedEquipmentFilter(Component $component)
    {
        $this->setEquipmentBachelors($component);
    }

    public function mount(Component $component, $model)
    {
        $component->model = $model;
        $component->fill([
            "equipments" => [],
            "equipmentRelated" => $model->equipments,
            "equipmentTypes" => $this->getEquipmentTypes(),
            "empty_text" => "Utilice los filtros para ver el listado de equipos",
            "equipmentPicked" => false,
            "equipmentId" => null,
            "equipmentBachelors" => [],
            "selectedRows" => [],
            "equipmentFilter" => null,
            "equipmentTypeId" => null
        ]);
    }

    private function refreshEquipmentType($component)
    {
        $this->setEquipmentBachelors($component);
        $component->selectedRows = [];
    }
}
