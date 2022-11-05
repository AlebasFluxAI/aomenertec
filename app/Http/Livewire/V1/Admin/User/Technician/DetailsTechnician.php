<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Services\V1\Admin\User\Technician\TechnicianDetailsService;
use App\Models\V1\Technician;
use Livewire\Component;

class DetailsTechnician extends Component
{
    public $model;
    private $detailsTechnicianService;


    public function __construct($id = null)
    {
        $this->detailsTechnicianService = TechnicianDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(Technician $technician)
    {
        $this->detailsTechnicianService->mount($this, $technician);
    }

    public function conditionalDeleteEquipment($id)
    {
        return $this->detailsTechnicianService->conditionalDeleteEquipment($this, $id);
    }

    public function deleteEquipment($id)
    {
        $this->detailsTechnicianService->deleteEquipment($this, $id);
    }

    public function conditionalRemoveEquipmentTechnician($id)
    {
        return $this->detailsTechnicianService->conditionalRemoveEquipmentTechnician($this, $id);
    }

    public function removeEquipmentTechnician($id)
    {
        $this->detailsTechnicianService->removeEquipmentTechnician($this, $id);
    }

    public function conditionalMonitoring($id)
    {
        return $this->detailsTechnicianService->conditionalMonitoring($this, $id);
    }

    public function conditionalDeleteClient($id)
    {
        return $this->detailsTechnicianService->conditionalDeleteClient($this, $id);
    }

    public function deleteClient($id)
    {
        $this->detailsTechnicianService->deleteclient($this, $id);
    }

    public function deprecateEquipment($id)
    {
        return $this->detailsTechnicianService->deprecateEquipment($id);
    }

    public function conditionalEquipmentDeprecate($id)
    {

        return $this->detailsTechnicianService->conditionalEquipmentDeprecate($id);
    }


    public function conditionalEquipmentRepaired($id)
    {
        return $this->detailsTechnicianService->conditionalEquipmentRepaired($id);
    }

    public function repairEquipment($id)
    {
        return $this->detailsTechnicianService->repairEquipment($id);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.technician.detail-technician')
            ->extends('layouts.v1.app');
    }
}
