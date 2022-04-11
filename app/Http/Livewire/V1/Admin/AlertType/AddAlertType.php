<?php

namespace App\Http\Livewire\V1\Admin\AlertType;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\AlertType\AlertTypeAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertAddService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeAddService;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class AddAlertType extends Component
{
    public $name;
    public $value;
    public $unit;


    private $addEquipmentAlertTypeService;

    public function __construct($id = null)
    {
        $this->addEquipmentAlertTypeService = AlertTypeAddService::getInstance();
        parent::__construct($id);
    }


    public function mount()
    {
        $this->addEquipmentAlertTypeService->mount($this);
    }


    public function submitForm()
    {
        $this->addEquipmentAlertTypeService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.administrar.v1.alertType.add-alert-type')
            ->extends('layouts.v1.app');
    }
}
