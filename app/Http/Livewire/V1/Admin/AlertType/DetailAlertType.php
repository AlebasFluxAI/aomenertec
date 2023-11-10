<?php

namespace App\Http\Livewire\V1\Admin\AlertType;

use App\Http\Services\V1\Admin\AlertType\AlertTypeDetailService;
use App\Models\V1\AlertType;
use Livewire\Component;
use function view;

class DetailAlertType extends Component
{
    public $alertType;
    private $detailAlertTypeService;


    public function __construct($id = null)
    {
        $this->detailAlertTypeService = AlertTypeDetailService::getInstance();
        parent::__construct($id);
    }

    public function mount(AlertType $alertType)
    {
        $this->detailAlertTypeService->mount($this, $alertType);
    }

    public function edit()
    {
        $this->detailAlertTypeService->edit($this);
    }

    public function render()
    {
        return view('livewire.administrar.v1.alertType.detail-alert-type')
            ->extends('layouts.v1.app');
    }
}
