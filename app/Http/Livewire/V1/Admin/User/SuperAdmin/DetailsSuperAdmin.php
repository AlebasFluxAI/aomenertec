<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin;

use App\Http\Services\V1\Admin\User\SuperAdmin\SuperAdminDetailsService;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class DetailsSuperAdmin extends Component
{
    public $model;
    public $admins;
    public $network_operators;
    public $equipment;
    private $superAdminDetailService;


    public function __construct($id = null)
    {
        $this->superAdminDetailService = SuperAdminDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(SuperAdmin $superAdmin)
    {
        $this->superAdminDetailService->mount($this, $superAdmin);
    }

    public function conditionalDeleteAdmin($adminId)
    {
        return $this->superAdminDetailService->conditionalDeleteAdmin($this, $adminId);
    }

    public function deleteAdmin($adminId)
    {
        return $this->superAdminDetailService->deleteAdmin($this, $adminId);
    }

    public function conditionalEquipmentDeprecate()
    {
        return;
    }

    public function disableAdmin($id)
    {
        $this->superAdminDetailService->disableAdmin($this, $id);
    }

    public function getEnabledAdmin($id)
    {
        return $this->superAdminDetailService->getEnabledAdmin($this, $id);
    }

    public function getEnabledAuxAdmin($id)
    {
        return $this->superAdminDetailService->getEnabledAuxAdmin($this, $id);
    }

    public function removeEquipmentAdmin($id)
    {
        $this->superAdminDetailService->removeEquipmentAdmin($this, $id);
    }

    public function conditionalRemoveEquipmentAdmin($id)
    {
        return $this->superAdminDetailService->conditionalRemoveEquipmentAdmin($this, $id);
    }

    public function conditionalDeleteNetworkOperator($adminId)
    {
        return $this->superAdminDetailService->conditionalDeleteNetworkOperator($this, $adminId);
    }

    public function deleteNetworkOperator($networkOperatorId)
    {
        $this->superAdminDetailService->deleteNetworkOperator($this, $networkOperatorId);
    }

    public function disableNetworkOperator($id)
    {
        $this->superAdminDetailService->disableNetworkOperator($this, $id);
    }

    public function getEnabledNetworkOperator($id)
    {
        return $this->superAdminDetailService->getEnabledNetworkOperator($this, $id);
    }

    public function getEnabledAuxNetworkOperator($id)
    {
        return $this->superAdminDetailService->getEnabledAuxNetworkOperator($this, $id);
    }

    public function conditionalLinkEquipmentNetworkOperator($networkOperatorId)
    {
        return $this->superAdminDetailService->conditionalLinkEquipmentNetworkOperator($this, $networkOperatorId);
    }

    public function conditionalDeleteTechnician($id)
    {
        return $this->superAdminDetailService->conditionalDeleteTechnician($this, $id);
    }

    public function removeEquipmentNetworkOperator($id)
    {
        $this->superAdminDetailService->removeEquipmentNetworkOperator($this, $id);
    }

    public function conditionalRemoveEquipmentNetworkOperator($id)
    {
        return $this->superAdminDetailService->conditionalRemoveEquipmentNetworkOperator($this, $id);
    }

    public function conditionalDeleteEquipment($id)
    {
        return $this->superAdminDetailService->conditionalDeleteEquipment($this, $id);
    }

    public function deleteEquipment($id)
    {
        $this->superAdminDetailService->deleteEquipment($this, $id);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.super.detail-super-admin')
            ->extends('layouts.v1.app');
    }
}
