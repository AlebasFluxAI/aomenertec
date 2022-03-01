<?php

namespace App\Http\Livewire\V1\Admin\EquipmentAlert;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertAddService;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class AddEquipmentAlert extends Component
{
    public $type;
    public $interval;
    public $equipments;
    public $equipmentId;
    public $picked;
    protected $rules = [
        'equipmentName' => 'required|min:2',
        'equipmentSerial' => 'unique:equipments,serial'
    ];
    private $addEquipmentAlertService;

    public function __construct($id = null)
    {
        $this->addEquipmentAlertService = EquipmentAlertAddService::getInstance();
        parent::__construct($id);
    }


    public function mount()
    {
        $this->fill([
            'type' => "alert",
            'interval' => null,
            'equipments' => [],
            'equipmentId' => null,
            'picked' => [],

        ]);
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


    public function render()
    {
        return view('livewire.administrar.v1.equipmentAlert.add-equipment-alert')
            ->extends('layouts.v1.app');
    }
}
