<?php

namespace App\Http\Livewire\V1\Admin\EquipmentType;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeAddService;
use Livewire\Component;
use function view;

class AddEquipmentType extends Component
{
    public $type;
    public $description;


    private $addEquipmentTypeService;

    public function __construct($id = null)
    {
        $this->addEquipmentTypeService = EquipmentTypeAddService::getInstance();
        parent::__construct($id);
    }


    public function mount()
    {
        $this->addEquipmentTypeService->mount($this);
    }


    public function submitForm()
    {
        $this->addEquipmentTypeService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.equipmentType.add-equipment-type',)
            ->extends('layouts.v1.app');
    }
}
