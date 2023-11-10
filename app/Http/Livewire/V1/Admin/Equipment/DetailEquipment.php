<?php

namespace App\Http\Livewire\V1\Admin\Equipment;

use App\Http\Services\V1\Admin\Equipment\EquipmentDetailService;
use App\Models\V1\Equipment;
use Livewire\Component;
use function view;

class DetailEquipment extends Component
{
    public $equipment;
    private $detailEquipmentService;


    public function __construct($id = null)
    {
        $this->detailEquipmentService = EquipmentDetailService::getInstance();
        parent::__construct($id);
    }

    public function mount(Equipment $equipment)
    {
        $this->detailEquipmentService->mount($this, $equipment);
    }

    public function conditionalDeleteTechnician($id)
    {
        return $this->detailEquipmentService->conditionalDeleteTechnician($this, $id);
    }

    public function deleteTechnician($id)
    {
        $this->detailEquipmentService->deleteTechnician($this, $id);
    }

    public function disableTechnician($id)
    {
        $this->detailEquipmentService->disableTechnician($this, $id);
    }

    public function getEnabledTechnician($id)
    {
        return $this->detailEquipmentService->getEnabledTechnician($this, $id);
    }

    public function getEnabledAuxTechnician($id)
    {
        return $this->detailEquipmentService->getEnabledAuxTechnician($this, $id);
    }

    public function conditionalLinkEquipmentTechnician($id)
    {
        return $this->detailEquipmentService->conditionalLinkEquipmentTechnician($this, $id);
    }

    public function conditionalLinkClientsTechnician($id)
    {
        return $this->detailEquipmentService->conditionalLinkClientsTechnician($this, $id);
    }

    public function removeEquipmentNetworkOperator($id)
    {
        $this->detailEquipmentService->removeEquipmentNetworkOperator($this, $id);
    }

    public function conditionalRemoveEquipmentNetworkOperator($id)
    {
        return $this->detailEquipmentService->conditionalRemoveEquipmentNetworkOperator($this, $id);
    }

    public function conditionalMonitoring($id)
    {
        return $this->detailEquipmentService->conditionalMonitoring($this, $id);
    }

    public function conditionalDeleteClient($id)
    {
        return $this->detailEquipmentService->conditionalDeleteClient($this, $id);
    }

    public function deleteClient($id)
    {
        $this->detailEquipmentService->deleteclient($this, $id);
    }

    public function conditionalDeleteAdmin($adminId)
    {
        return $this->detailEquipmentService->conditionalDeleteAdmin($this, $adminId);
    }

    public function deleteAdmin($adminId)
    {
        return $this->detailEquipmentService->deleteAdmin($this, $adminId);
    }

    public function disableAdmin($id)
    {
        $this->detailEquipmentService->disableAdmin($this, $id);
    }

    public function getEnabledAdmin($id)
    {
        return $this->detailEquipmentService->getEnabledAdmin($this, $id);
    }

    public function getEnabledAuxAdmin($id)
    {
        return $this->detailEquipmentService->getEnabledAuxAdmin($this, $id);
    }

    public function removeEquipmentAdmin($id)
    {
        $this->detailEquipmentService->removeEquipmentAdmin($this, $id);
    }

    public function conditionalRemoveEquipmentAdmin($id)
    {
        return $this->detailEquipmentService->conditionalRemoveEquipmentAdmin($this, $id);
    }

    public function conditionalDeleteNetworkOperator($adminId)
    {
        return $this->detailEquipmentService->conditionalDeleteNetworkOperator($this, $adminId);
    }

    public function deleteNetworkOperator($networkOperatorId)
    {
        $this->detailEquipmentService->deleteNetworkOperator($this, $networkOperatorId);
    }

    public function disableNetworkOperator($id)
    {
        $this->detailEquipmentService->disableNetworkOperator($this, $id);
    }

    public function getEnabledNetworkOperator($id)
    {
        return $this->detailEquipmentService->getEnabledNetworkOperator($this, $id);
    }

    public function getEnabledAuxNetworkOperator($id)
    {
        return $this->detailEquipmentService->getEnabledAuxNetworkOperator($this, $id);
    }

    public function conditionalLinkEquipmentNetworkOperator($networkOperatorId)
    {
        return $this->detailEquipmentService->conditionalLinkEquipmentNetworkOperator($this, $networkOperatorId);
    }


    public function render()
    {
        return view('livewire.v1.admin.equipment.detail-equipment')
            ->extends('layouts.v1.app');
    }
}
