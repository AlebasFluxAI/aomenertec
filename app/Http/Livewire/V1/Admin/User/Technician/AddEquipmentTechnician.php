<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Livewire\V1\Admin\User\AssignedEquipmentInterface;
use App\Http\Services\V1\Admin\User\Technician\TechnicianAddEquipmentService;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\Traits\TableRowCheckTrait;
use App\Models\V1\Technician;
use Livewire\Component;

class AddEquipmentTechnician extends Component implements AssignedEquipmentInterface
{
    use TableRowCheckTrait;
    use EquipmentAssignationTrait;

    public $assignationType = Technician::class;
    public $model;
    public $type;
    public $equipmentRelated;
    public $equipment_id;
    public $equipmentId;
    public $equipments;


    private $technicianAddEquipmentTypeService;

    public function __construct($id = null)
    {
        $this->technicianAddEquipmentTypeService = TechnicianAddEquipmentService::getInstance();
        parent::__construct($id);
    }

    public function mount(Technician $technician)
    {
        $this->technicianAddEquipmentTypeService->mount($this, $technician);
    }

    public function submitForm()
    {
        $this->technicianAddEquipmentTypeService->submitForm($this);
    }


    public function pass()
    {
    }

    public function render()
    {
        return view('livewire.v1.admin.user.add-equipment-generic')
            ->extends('layouts.v1.app');
    }

    public function getPageTitle()
    {
        return "Equipos de técnico";
    }

    public function getNavOptions()
    {
        return [
            ["button_align" => "right",
                "click_action" => "",
                "button_icon" => "fas fa-list",
                "button_content" => "Ver listado",
                "target_route" => "administrar.v1.usuarios.tecnicos.listado",
            ],

        ];
    }

    public function deleteEquipmentAssigned($id)
    {
        $this->technicianAddEquipmentTypeService->deleteEquipmentAssigned($this, $id);
    }

    public function updatedEquipmentTypeId()
    {
        $this->technicianAddEquipmentTypeService->updatedEquipmentTypeId($this);
    }

    public function updatedEquipmentFilter()
    {
        $this->technicianAddEquipmentTypeService->updatedEquipmentFilter($this);
    }

    public function updatedSelectedAll()
    {
        $this->technicianAddEquipmentTypeService->updatedSelectedAll($this);
    }


    public function getCardTitle()
    {
        return "Técnico";
    }
}
