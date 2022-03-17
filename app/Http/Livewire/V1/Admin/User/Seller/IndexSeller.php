<?php

namespace App\Http\Livewire\V1\Admin\User\Seller;


use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Models\V1\Supervisor;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class IndexSeller extends Component
{
    use WithPagination;


    private $indexEquipmentService;

    public function __construct($id = null)
    {
        $this->indexEquipmentService = EquipmentIndexService::getInstance();
        parent::__construct($id);
    }

    public function getEquipments()
    {
        return $this->indexEquipmentService->getEquipments();
    }

    public function details($id)
    {
        $this->indexEquipmentService->details($this, $id);
    }

    public function edit($id)
    {
        $this->indexEquipmentService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexEquipmentService->delete($this, $id);

    }

    public function render()
    {
        return view('livewire.administrar.v1.equipment.index-equipment', [
            "equipments" => Supervisor::paginate(15)
        ])->extends('layouts.v1.app');
    }
}
