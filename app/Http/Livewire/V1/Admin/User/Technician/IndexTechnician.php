<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\User\Technician\TechnicianIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\Technician;
use App\Models\V1\EquipmentType;
use App\Models\V1\Admin;
use Livewire\Component;
use Livewire\WithPagination;

class IndexTechnician extends Component
{
    use WithPagination;
    use FilterTrait;


    private $indexTechnicianService;

    public function __construct($id = null)
    {
        $this->indexTechnicianService = TechnicianIndexService::getInstance();
        parent::__construct($id);
    }


    public function edit($id)
    {
        $this->indexTechnicianService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexTechnicianService->delete($this, $id);
    }

    public function details($id)
    {
        $this->indexTechnicianService->details($this, $id);
    }

    public function addClients($id)
    {
        $this->indexTechnicianService->addClients($this, $id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.user.technician.index-technician',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexTechnicianService->getData($this);
    }
}
