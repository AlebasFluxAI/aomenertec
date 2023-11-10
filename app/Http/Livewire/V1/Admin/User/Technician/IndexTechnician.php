<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Services\V1\Admin\User\Technician\TechnicianIndexService;
use App\Models\Traits\FilterTrait;
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

    public function deleteTechnician($id)
    {
        $this->indexTechnicianService->deleteTechnician($this, $id);
    }

    public function disableTechnician($id)
    {
        $this->indexTechnicianService->disableTechnician($this, $id);
    }

    public function getEnabledTechnician($id)
    {
        return $this->indexTechnicianService->getEnabledTechnician($this, $id);
    }

    public function getEnabledAuxTechnician($id)
    {
        return $this->indexTechnicianService->getEnabledAuxTechnician($this, $id);
    }

    public function conditionalDeleteTechnician($id)
    {
        return $this->indexTechnicianService->conditionalDeleteTechnician($this, $id);
    }

    public function conditionalLinkEquipmentTechnician($id)
    {
        return $this->indexTechnicianService->conditionalLinkEquipmentTechnician($this, $id);
    }

    public function conditionalLinkClientsTechnician($id)
    {
        return $this->indexTechnicianService->conditionalLinkClientsTechnician($this, $id);
    }
}
