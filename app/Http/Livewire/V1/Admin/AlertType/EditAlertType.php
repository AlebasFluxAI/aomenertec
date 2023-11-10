<?php

namespace App\Http\Livewire\V1\Admin\AlertType;

use App\Http\Services\V1\Admin\AlertType\AlertTypeEditService;
use App\Models\V1\AlertType;
use Livewire\Component;
use function view;

class EditAlertType extends Component
{
    public $alertType;
    public $name;
    public $value;
    public $unit;
    private $editAlertTypeService;

    public function __construct($id = null)
    {
        $this->editAlertTypeService = AlertTypeEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(AlertType $alertType)
    {
        $this->editAlertTypeService->mount($this, $alertType);
    }

    public function submitForm()
    {
        $this->editAlertTypeService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.administrar.v1.alertType.edit-alert-type')
            ->extends('layouts.v1.app');
    }
}
