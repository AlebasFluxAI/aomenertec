<?php

namespace App\Http\Livewire\V1\Admin\EquipmentType;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertAddService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeAddService;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithPagination;
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
        return view('livewire.v1.admin.equipmentType.add-equipment-type', )
            ->extends('layouts.v1.app');
    }
}
