<?php

namespace App\Http\Livewire\V1\Admin\EquipmentType;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeDetailService;
use App\Models\V1\EquipmentType;
use Livewire\Component;
use function view;

class DetailEquipmentType extends Component
{
    public $model;
    private $detailAlertTypeService;


    public function __construct($id = null)
    {
        $this->detailAlertTypeService = EquipmentTypeDetailService::getInstance();
        parent::__construct($id);
    }

    public function mount(EquipmentType $equipmentType)
    {
        $this->detailAlertTypeService->mount($this, $equipmentType);
    }

    public function edit()
    {
        $this->detailAlertTypeService->edit($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.equipmentType.detail-equipment-type',)
            ->extends('layouts.v1.app');
    }
}
