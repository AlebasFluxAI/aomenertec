<?php

namespace App\Http\Livewire\V1\Admin\EquipmentType;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeEditService;
use App\Models\V1\EquipmentType;
use Livewire\Component;
use function view;

class EditEquipmentType extends Component
{
    public $model;
    public $type;
    public $description;
    private $editAlertTypeService;

    public function __construct($id = null)
    {
        $this->editAlertTypeService = EquipmentTypeEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(EquipmentType $equipmentType)
    {
        $this->editAlertTypeService->mount($this, $equipmentType);
    }

    public function submitForm()
    {
        $this->editAlertTypeService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.equipmentType.edit-equipment-type',)
            ->extends('layouts.v1.app');
    }
}
