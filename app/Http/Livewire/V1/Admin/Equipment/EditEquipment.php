<?php

namespace App\Http\Livewire\V1\Admin\Equipment;

use App\Http\Services\V1\Admin\Equipment\EquipmentEditService;
use App\Models\Traits\MenuTrait;
use App\Models\V1\Equipment;
use Livewire\Component;
use function view;

class EditEquipment extends Component
{
    public $equipmentSerial;
    public $equipment;
    public $serial;
    public $description;
    public $equipmentName;
    public $equipmentDescription;
    public $equipmentTypeId;
    public $equipmentTypes;
    public $picked;
    public $status;
    public $equipment_status;
    public $has_multiple_connection;
    private $editEquipmentService;


    public function __construct($id = null)
    {
        $this->editEquipmentService = EquipmentEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(Equipment $equipment)
    {
        $this->editEquipmentService->mount($this, $equipment);
    }

    public function setEquipmentType($equipmentType)
    {
        $this->editEquipmentService->setEquipmentType($this, $equipmentType);
    }


    public function updatedEquipmentTypeId()
    {
        $this->editEquipmentService->updatedEquipmentTypeId($this);
    }

    public function submitForm()
    {
        $this->editEquipmentService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.equipment.edit-equipment')
            ->extends('layouts.v1.app');
    }
}
