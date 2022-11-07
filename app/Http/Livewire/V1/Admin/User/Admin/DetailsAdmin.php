<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AdminDetailsService;
use App\Models\V1\Admin;
use Livewire\Component;

class DetailsAdmin extends Component
{
    public $admin;
    public $clients;
    private $adminDetailService;


    public function __construct()
    {
        parent::__construct();
        $this->adminDetailService = AdminDetailsService::getInstance();
    }

    public function mount(Admin $admin)
    {
        $this->adminDetailService->mount($this, $admin);
    }

    public function conditionalDeleteNetworkOperator($adminId)
    {
        return $this->adminDetailService->conditionalDeleteNetworkOperator($this, $adminId);
    }

    public function deleteNetworkOperator($networkOperatorId)
    {
        $this->adminDetailService->deleteNetworkOperator($this, $networkOperatorId);
    }

    public function disableNetworkOperator($id)
    {
        $this->adminDetailService->disableNetworkOperator($this, $id);
    }

    public function getEnabledNetworkOperator($id)
    {
        return $this->adminDetailService->getEnabledNetworkOperator($this, $id);
    }

    public function getEnabledAuxNetworkOperator($id)
    {
        return $this->adminDetailService->getEnabledAuxNetworkOperator($this, $id);
    }

    public function conditionalLinkEquipmentNetworkOperator($networkOperatorId)
    {
        return $this->adminDetailService->conditionalLinkEquipmentNetworkOperator($this, $networkOperatorId);
    }

    public function removeEquipmentAdmin($id)
    {
        $this->adminDetailService->removeEquipmentAdmin($this, $id);
    }

    public function conditionalRemoveEquipmentAdmin($id)
    {
        return $this->adminDetailService->conditionalRemoveEquipmentAdmin($this, $id);
    }

    public function conditionalDeleteEquipment($id)
    {
        return $this->adminDetailService->conditionalDeleteEquipment($this, $id);
    }

    public function deleteEquipment($id)
    {
        $this->adminDetailService->deleteEquipment($this, $id);
    }

    public function conditionalMonitoring($id)
    {
        return $this->adminDetailService->conditionalMonitoring($this, $id);
    }

    public function conditionalDeleteClient($id)
    {
        return $this->adminDetailService->conditionalDeleteClient($this, $id);
    }

    public function deleteClient($id)
    {
        $this->adminDetailService->deleteclient($this, $id);
    }

    public function deprecateEquipment($id)
    {
        return $this->adminDetailService->deprecateEquipment($id);
    }

    public function conditionalEquipmentDeprecate($id)
    {

        return $this->adminDetailService->conditionalEquipmentDeprecate($id);
    }


    public function conditionalEquipmentRepaired($id)
    {
        return $this->adminDetailService->conditionalEquipmentRepaired($id);
    }

    public function repairEquipment($id)
    {
        return $this->adminDetailService->repairEquipment($id);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.admin.detail-admin')
            ->extends('layouts.v1.app');
    }
}
