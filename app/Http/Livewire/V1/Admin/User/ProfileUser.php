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

    public function deleteNetworkOperator($networkOperatorId)
    {
        $this->profileUserService->deleteNetworkOperator($this, $networkOperatorId);
    }

    public function conditionalNetworkOperatorDelete($networkOperatorId)
    {
        return $this->profileUserService->conditionalNetworkOperatorDelete($networkOperatorId);
    }

    public function conditionalMonitoring($clientId)
    {
        return $this->profileUserService->conditionalMonitoring($clientId);
    }

    public function deleteAdminConditional($adminId)
    {
        return $this->profileUserService->deleteAdminConditional($this, $adminId);
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
