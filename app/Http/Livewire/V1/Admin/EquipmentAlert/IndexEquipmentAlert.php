<?php

namespace App\Http\Livewire\V1\Admin\EquipmentAlert;

use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Models\V1\EquipmentAlert;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class IndexEquipmentAlert extends Component
{
    use WithPagination;


    private $indexEquipmentService;

    public function __construct($id = null)
    {
        $this->indexEquipmentService = EquipmentAlertIndexService::getInstance();
        parent::__construct($id);
    }

    public function edit($id)
    {
        $this->indexEquipmentService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexEquipmentService->delete($this, $id);
    }

    public function details($id)
    {
        $this->indexEquipmentService->details($this, $id);
    }

    public function render()
    {
        return view('livewire.administrar.v1.equipmentAlert.index-equipment-alert', [
            "equipmentAlerts" => EquipmentAlert::pagination()
        ])->extends('layouts.v1.app');
    }
}
