<?php

namespace App\Http\Livewire\V1\Admin\EquipmentAlert;

use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertEditService;
use App\Models\V1\EquipmentAlert;
use Livewire\Component;
use function view;

class EditEquipmentAlert extends Component
{
    public $type;
    public $value;
    public $equipments;
    public $equipmentId;
    public $picked;
    public $alertTypes = [];
    public $alertType;
    public $model;
    protected $rules = [
        'equipmentName' => 'required|min:2',
        'equipmentSerial' => 'unique:equipments,serial'
    ];
    private $addEquipmentAlertService;

    public function __construct($id = null)
    {
        $this->addEquipmentAlertService = EquipmentAlertEditService::getInstance();
        parent::__construct($id);
    }


    public function mount(EquipmentAlert $equipmentAlert)
    {
        $this->addEquipmentAlertService->mount($this, $equipmentAlert);
    }

    public function updatedEquipmentId()
    {
        $this->addEquipmentAlertService->updatedEquipmentId($this);
    }

    public function setEquipment($equipment)
    {
        $this->addEquipmentAlertService->setEquipment($this, $equipment);
    }

    public function updatedSelectedState($state)
    {
        $this->addEquipmentAlertService->updatedSelectedState($this, $state);
    }

    public function submitForm()
    {
        $this->addEquipmentAlertService->submitForm($this);
    }

    public function refreshAlertTypes()
    {
        $this->addEquipmentAlertService->refreshAlertTypes($this);
    }


    public function render()
    {
        return view('livewire.administrar.v1.equipmentAlert.edit-equipment-alert')
            ->extends('layouts.v1.app');
    }
}
