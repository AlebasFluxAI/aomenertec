<?php

namespace App\Http\Livewire\V1\Admin\Equipment;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Models\Traits\MenuTrait;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithPagination;
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


    protected $rules = [
        'equipmentName' => 'required|min:2',
        'equipmentSerial' => 'unique:equipments,serial'
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

    public function render()
    {
        return view('livewire.v1.admin.equipment.add-equipment')
            ->extends('layouts.v1.app');
    }
}
