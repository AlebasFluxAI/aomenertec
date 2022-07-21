<?php

namespace App\Http\Livewire\V1\Admin\User;

use App\Http\Services\V1\Admin\User\EditUserService;
use App\Http\Services\V1\Admin\User\ProfileUserService;
use Livewire\Component;
use function view;

class ProfileUser extends Component
{
    public $model;
    public $admins;
    private $profileUserService;

    public function __construct()
    {
        parent::__construct();
        $this->profileUserService = ProfileUserService::getInstance();
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

    public function deleteNetworkOperator($networkOperatorId)
    {
        $this->profileUserService->deleteNetworkOperator($this, $networkOperatorId);
    }

    public function conditionalDeleteNetworkOperator($adminId)
    {
        return $this->profileUserService->conditionalDeleteNetworkOperator($this, $adminId);
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

    public function conditionalMonitoring($clientId)
    {
        return $this->profileUserService->conditionalMonitoring($clientId);
    }

    public function conditionalDeleteAdmin($adminId)
    {
        return $this->profileUserService->conditionalDeleteAdmin($this, $adminId);
    }

    public function deleteAdmin($adminId)
    {
        return $this->profileUserService->deleteAdmin($this, $adminId);
    }

    public function blinkSupportPqrAvailability($supportId)
    {

        return $this->profileUserService->blinkSupportPqrAvailability($this, $supportId);
    }

    public function conditionalLinkEquipmentNetworkOperator($networkOperatorId)
    {
        return $this->profileUserService->conditionalLinkEquipmentNetworkOperator($this, $networkOperatorId);
    }

    public function mount()
    {
        $this->profileUserService->mount($this);
    }


    public function render()
    {
        return view($this->profileUserService->getViewName())
            ->extends('layouts.v1.app');
    }
}
