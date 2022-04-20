<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\User\Technician\TechnicianIndexService;
use App\Models\V1\Technician;
use App\Models\V1\EquipmentType;
use App\Models\V1\Admin;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTechnician extends Component
{
    use WithPagination;


    private $indexEquipmentService;

    public function __construct($id = null)
    {
        $this->indexEquipmentService = TechnicianIndexService::getInstance();
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

    public function addClients($id)
    {
        $this->indexEquipmentService->addClients($this, $id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.user.technician.index-technician',
            [
                "data" => Technician::paginate(15)
            ]
        )->extends('layouts.v1.app');
    }
}
