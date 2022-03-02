<?php

namespace App\Http\Livewire\V1\Admin\EquipmentAlert;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentAlert;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
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

    public function getEquipments()
    {
        return $this->indexEquipmentService->getEquipments();
    }

    public function editEquipment($id)
    {
        $this->indexEquipmentService->editEquipment($this, $id);
    }

    public function deleteEquipment($id)
    {
        $this->indexEquipmentService->deleteEquipment($this, $id);

    }

    public function render()
    {
        return view('livewire.administrar.v1.equipmentAlert.index-equipment-alert', [
            "equipmentAlerts" => EquipmentAlert::paginate(15)
        ])->extends('layouts.v1.app');
    }
}
