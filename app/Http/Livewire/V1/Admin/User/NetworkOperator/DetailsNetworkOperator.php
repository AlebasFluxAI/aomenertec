<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorDetailsService;
use App\Models\V1\NetworkOperator;
use Livewire\Component;

class DetailsNetworkOperator extends Component
{
    public $model;
    public $supervisors;
    private $detailsNetworkOperatorService;


    public function __construct($id = null)
    {
        $this->detailsNetworkOperatorService = NetworkOperatorDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(NetworkOperator $networkOperator)
    {
        $this->detailsNetworkOperatorService->mount($this, $networkOperator);
    }

    public function conditionalDeleteTechnician($id)
    {
        return $this->detailsNetworkOperatorService->conditionalDeleteTechnician($this, $id);
    }
    public function deleteTechnician($id)
    {
        $this->detailsNetworkOperatorService->deleteTechnician($this, $id);
    }
    public function disableTechnician($id)
    {
        $this->detailsNetworkOperatorService->disableTechnician($this, $id);
    }
    public function getEnabledTechnician($id)
    {
        return $this->detailsNetworkOperatorService->getEnabledTechnician($this, $id);
    }
    public function getEnabledAuxTechnician($id)
    {
        return $this->detailsNetworkOperatorService->getEnabledAuxTechnician($this, $id);
    }
    public function conditionalLinkEquipmentTechnician($id)
    {
        return $this->detailsNetworkOperatorService->conditionalLinkEquipmentTechnician($this, $id);
    }
    public function conditionalLinkClientsTechnician($id)
    {
        return $this->detailsNetworkOperatorService->conditionalLinkClientsTechnician($this, $id);
    }
    public function removeEquipmentNetworkOperator($id)
    {
        $this->detailsNetworkOperatorService->removeEquipmentNetworkOperator($this, $id);
    }
    public function conditionalRemoveEquipmentNetworkOperator($id)
    {
        return $this->detailsNetworkOperatorService->conditionalRemoveEquipmentNetworkOperator($this, $id);
    }

    public function conditionalDeleteSupervisor($id)
    {
        return $this->detailsNetworkOperatorService->conditionalDeleteSupervisor($this, $id);
    }
    public function deleteSupervisor($id)
    {
        $this->detailsNetworkOperatorService->deleteSupervisor($this, $id);
    }
    public function disableSupervisor($id)
    {
        $this->detailsNetworkOperatorService->disableSupervisor($this, $id);
    }
    public function conditionalLinkClientsSupervisor($id)
    {
        return $this->detailsNetworkOperatorService->conditionalLinkClientsSupervisor($this, $id);
    }
    public function getEnabledSupervisor($id)
    {
        return $this->detailsNetworkOperatorService->getEnabledSupervisor($this, $id);
    }
    public function getEnabledAuxSupervisor($id)
    {
        return $this->detailsNetworkOperatorService->getEnabledAuxSupervisor($this, $id);
    }

    public function conditionalDeleteEquipment($id){
        return $this->detailsNetworkOperatorService->conditionalDeleteEquipment($this, $id);
    }
    public function deleteEquipment($id)
    {
        $this->detailsNetworkOperatorService->deleteEquipment($this, $id);
    }

    public function conditionalMonitoring($id)
    {
        return $this->detailsNetworkOperatorService->conditionalMonitoring($this, $id);
    }
    public function conditionalDeleteClient($id)
    {
        return $this->detailsNetworkOperatorService->conditionalDeleteClient($this, $id);
    }
    public function deleteClient($id)
    {
        $this->detailsNetworkOperatorService->deleteclient($this, $id);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.network-operator.detail-network-operator')
            ->extends('layouts.v1.app');
    }
}
