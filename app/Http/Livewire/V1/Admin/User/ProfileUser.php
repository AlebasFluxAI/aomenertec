<?php

namespace App\Http\Livewire\V1\Admin\User;

use App\Http\Services\V1\Admin\User\ProfileUserService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use function view;

class ProfileUser extends Component
{
    use FilterTrait;

    public $model;
    public $admins;
    public $network_operators;
    public $equipment;
    public $supervisors;
    private $profileUserService;

    public function __construct()
    {
        parent::__construct();
        $this->profileUserService = ProfileUserService::getInstance();
    }

    public function conditionalDeleteAdmin($adminId)
    {
        return $this->profileUserService->conditionalDeleteAdmin($this, $adminId);
    }

    public function deleteAdmin($adminId)
    {
        return $this->profileUserService->deleteAdmin($this, $adminId);
    }

    public function disableAdmin($id)
    {
        $this->profileUserService->disableAdmin($this, $id);
    }

    public function getEnabledAdmin($id)
    {
        return $this->profileUserService->getEnabledAdmin($this, $id);
    }

    public function getEnabledAuxAdmin($id)
    {
        return $this->profileUserService->getEnabledAuxAdmin($this, $id);
    }

    public function removeEquipmentAdmin($id)
    {
        $this->profileUserService->removeEquipmentAdmin($this, $id);
    }

    public function conditionalRemoveEquipmentAdmin($id)
    {
        return $this->profileUserService->conditionalRemoveEquipmentAdmin($this, $id);
    }

    public function conditionalDeleteNetworkOperator($adminId)
    {
        return $this->profileUserService->conditionalDeleteNetworkOperator($this, $adminId);
    }

    public function deleteNetworkOperator($networkOperatorId)
    {
        $this->profileUserService->deleteNetworkOperator($this, $networkOperatorId);
    }

    public function disableNetworkOperator($id)
    {
        $this->profileUserService->disableNetworkOperator($this, $id);
    }

    public function getEnabledNetworkOperator($id)
    {
        return $this->profileUserService->getEnabledNetworkOperator($this, $id);
    }

    public function getEnabledAuxNetworkOperator($id)
    {
        return $this->profileUserService->getEnabledAuxNetworkOperator($this, $id);
    }

    public function conditionalLinkEquipmentNetworkOperator($networkOperatorId)
    {
        return $this->profileUserService->conditionalLinkEquipmentNetworkOperator($this, $networkOperatorId);
    }

    public function conditionalDeleteTechnician($id)
    {
        return $this->profileUserService->conditionalDeleteTechnician($this, $id);
    }

    public function removeEquipmentNetworkOperator($id)
    {
        $this->profileUserService->removeEquipmentNetworkOperator($this, $id);
    }

    public function conditionalRemoveEquipmentNetworkOperator($id)
    {
        return $this->profileUserService->conditionalRemoveEquipmentNetworkOperator($this, $id);
    }

    public function deleteTechnician($id)
    {
        $this->profileUserService->deleteTechnician($this, $id);
    }

    public function disableTechnician($id)
    {
        $this->profileUserService->disableTechnician($this, $id);
    }

    public function getEnabledTechnician($id)
    {
        return $this->profileUserService->getEnabledTechnician($this, $id);
    }

    public function getEnabledAuxTechnician($id)
    {
        return $this->profileUserService->getEnabledAuxTechnician($this, $id);
    }

    public function conditionalLinkEquipmentTechnician($id)
    {
        return $this->profileUserService->conditionalLinkEquipmentTechnician($this, $id);
    }

    public function conditionalLinkClientsTechnician($id)
    {
        return $this->profileUserService->conditionalLinkClientsTechnician($this, $id);
    }

    public function removeEquipmentTechnician($id)
    {
        $this->profileUserService->removeEquipmentTechnician($this, $id);
    }

    public function conditionalRemoveEquipmentTechnician($id)
    {
        return $this->profileUserService->conditionalRemoveEquipmentTechnician($this, $id);
    }

    public function conditionalDeleteSupervisor($id)
    {
        return $this->profileUserService->conditionalDeleteSupervisor($this, $id);
    }

    public function deleteSupervisor($id)
    {
        $this->profileUserService->deleteSupervisor($this, $id);
    }

    public function disableSupervisor($id)
    {
        $this->profileUserService->disableSupervisor($this, $id);
    }

    public function conditionalLinkClientsSupervisor($id)
    {
        return $this->profileUserService->conditionalLinkClientsSupervisor($this, $id);
    }

    public function getEnabledSupervisor($id)
    {
        return $this->profileUserService->getEnabledSupervisor($this, $id);
    }

    public function getEnabledAuxSupervisor($id)
    {
        return $this->profileUserService->getEnabledAuxSupervisor($this, $id);
    }

    public function conditionalDeleteEquipment($id)
    {
        return $this->profileUserService->conditionalDeleteEquipment($this, $id);
    }

    public function deleteEquipment($id)
    {
        $this->profileUserService->deleteEquipment($this, $id);
    }


    public function conditionalMonitoring($clientId)
    {
        return $this->profileUserService->conditionalMonitoring($clientId);
    }

    public function blinkSupportPqrAvailability($supportId)
    {
        return $this->profileUserService->blinkSupportPqrAvailability($this, $supportId);
    }

    public function mount()
    {
        $this->profileUserService->mount($this);
    }


    public function changeSubsidy($event, $stratum_id)
    {
        return $this->profileUserService->getSubsidy($this, $event, $stratum_id);
    }

    public function getSubsidy($stratum_id)
    {
        return $this->profileUserService->getSubsidy($this, $stratum_id);
    }

    public function changeCredit($event, $stratum_id)
    {
        return $this->profileUserService->changeCredit($this, $event, $stratum_id);
    }

    public function changeValue($event, $stratum_id)
    {
        return $this->profileUserService->changeValue($this, $event, $stratum_id);
    }

    public function getCredit($stratum_id)
    {
        return $this->profileUserService->getCredit($this, $stratum_id);
    }

    public function getValue($stratum_id)
    {
        return $this->profileUserService->getValue($this, $stratum_id);
    }

    public function conditionalEquipmentDeprecate($id)
    {

        return $this->profileUserService->conditionalEquipmentDeprecate($id);
    }

    public function deprecateEquipment($id)
    {

        return $this->profileUserService->deprecateEquipment($id);
    }


    public function conditionalEquipmentRepaired($id)
    {
        return $this->profileUserService->conditionalEquipmentRepaired($id);
    }

    public function repairEquipment($id)
    {
        return $this->profileUserService->repairEquipment($id);
    }

    public function render()
    {
        return view($this->profileUserService->getViewName())
            ->extends('layouts.v1.app');
    }
}
