<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Services\V1\Admin\User\Technician\TechnicianAddEquipmentService;
use App\Models\V1\Technician;
use Livewire\Component;

class AddEquipmentTechnician extends Component
{
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

    public function updatedType()
    {
        $this->technicianAddEquipmentTypeService->updatedType($this);
    }

    public function assignType($client)
    {
        $this->technicianAddEquipmentTypeService->assignType($this, $client);
    }


    public function delete($id)
    {
        $this->technicianAddEquipmentTypeService->delete($this, $id);
    }

    public function pass()
    {
    }

    public function render()
    {
        return view('livewire.v1.admin.user.technician.add-equipment-technician')
            ->extends('layouts.v1.app');
    }
}
