<?php

namespace App\Http\Livewire\V1\Admin\Equipment;

use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Models\Traits\MenuTrait;
use Livewire\Component;
use function view;

class AddEquipment extends Component
{
    public $equipmentSerial;
    public $serial;
    public $description;
    public $equipmentName;
    public $equipmentDescription;
    public $equipmentTypeId;
    public $equipmentTypes;
    public $picked;
    public $name;
    public $has_multiple_connection;


    protected $rules = [
        'name' => 'required|min:2',
        'serial' => 'unique:equipment,serial'
    ];
    private $addEquipmentService;

    public function __construct($id = null)
    {
        $this->addEquipmentService = EquipmentAddService::getInstance();
        parent::__construct($id);
    }


    public function mount()
    {
        $this->addEquipmentService->mount($this);
    }

    public function updatedEquipmentTypeId()
    {
        $this->addEquipmentService->updatedEquipmentTypeId($this);
    }

    public function setEquipmentType($equipmentType)
    {
        $this->addEquipmentService->setEquipmentType($this, $equipmentType);
    }

    public function updatedSelectedState($state)
    {
        $this->addEquipmentService->updatedSelectedState($this, $state);
    }

    public function submitForm()
    {
        $this->addEquipmentService->submitForm($this);
    }

    public function updatingSearch()
    {
        $this->addEquipmentService->updatingSearch($this);
    }

    public function refreshEquipmentTypes()
    {
    }

    public function render()
    {
        return view('livewire.v1.admin.equipment.add-equipment')
            ->extends('layouts.v1.app');
    }
}
