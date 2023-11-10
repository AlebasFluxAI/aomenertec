<?php

namespace App\Http\Livewire\V1\Admin\AlertType;

use App\Http\Services\V1\Admin\AlertType\AlertTypeAddService;
use Livewire\Component;
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
