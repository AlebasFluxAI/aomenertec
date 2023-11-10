<?php

namespace App\Http\Livewire\V1\Admin\EquipmentType;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Models\V1\EquipmentAlert;
use App\Models\V1\EquipmentType;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class IndexEquipmentType extends Component
{
    use WithPagination;


    private $indexEquipmentService;

    public function __construct($id = null)
    {
        $this->indexEquipmentService = EquipmentTypeIndexService::getInstance();
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

    public function conditionalDelete($id)
    {
        return $this->indexEquipmentService->conditionalDelete($this, $id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.equipmentType.index-equipment-type',
            [
                "data" => EquipmentType::pagination()
            ]
        )->extends('layouts.v1.app');
    }
}
