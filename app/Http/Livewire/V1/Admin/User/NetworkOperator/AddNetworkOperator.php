<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\Admin\AdminAddService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorAddService;
use App\Http\Services\V1\Admin\User\Supervisor\SupervisorAddService;
use App\Models\Traits\ValidateUserFormTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddNetworkOperator extends Component
{
    use ValidateUserFormTrait;

    public $password;
    public $identification;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $message;
    public $picked;
    public $admins;
    public $admin_id;


    private $networkOperatorAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->networkOperatorAddService = NetworkOperatorAddService::getInstance();
    }

    public function mount()
    {
        $this->networkOperatorAddService->mount($this);
    }

    public function submitForm()
    {
        $this->networkOperatorAddService->submitForm($this);
    }

    public function updatedAdminId()
    {
        $this->networkOperatorAddService->updatedAdminId($this);
    }


    public function setAdminId($admin)
    {
        $this->networkOperatorAddService->setAdminId($this, $admin);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.network-operator.add-network-operator')
            ->extends('layouts.v1.app');
    }
}
