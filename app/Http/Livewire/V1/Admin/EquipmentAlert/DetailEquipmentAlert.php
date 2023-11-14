<?php

namespace App\Http\Livewire\V1\Admin\EquipmentAlert;

use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertDetailService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentDetailService;
use App\Models\V1\EquipmentAlert;
use Livewire\Component;
use function view;

class DetailEquipmentAlert extends Component
{
    public $model;
    private $detailEquipmentAlertService;


    public function __construct($id = null)
    {
        $this->detailEquipmentAlertService = EquipmentAlertDetailService::getInstance();
        parent::__construct($id);
    }

    public function mount(EquipmentAlert $equipmentAlert)
    {
        $this->detailEquipmentAlertService->mount($this, $equipmentAlert);
    }

    public function edit()
    {
        $this->detailEquipmentAlertService->edit($this);
    }

    public function render()
    {
        return view('livewire.administrar.v1.equipmentAlert.detail-equipment-alert')
            ->extends('layouts.v1.app');
    }
}
