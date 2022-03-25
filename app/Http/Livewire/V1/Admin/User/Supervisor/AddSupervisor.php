<?php

namespace App\Http\Livewire\V1\Admin\User\Supervisor;


use App\Http\Services\V1\Admin\User\SuperAdmin\NetworkOperatorAddService;
use App\Http\Services\V1\Admin\User\Supervisor\SupervisorAddService;
use Livewire\Component;

class AddSupervisor extends Component
{
    public $password;
    public $identification;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $message;
    public $picked;
    public $networkOperators;
    public $network_operator_id;


    protected $rules = [
        'identification' => 'required|min:6|unique:users,identification',
        'name' => 'required|min:8',
        'phone' => 'min:7',
        'email' => 'required|email|unique:users,email',
    ];
    private $supervisorAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->supervisorAddService = SupervisorAddService::getInstance();
    }


    public function updatedNetworkOperatorId()
    {
        $this->supervisorAddService->updatedNetworkOperatorId($this);
    }


    public function setNetworkOperatorId($admin)
    {
        $this->supervisorAddService->setNetworkOperatorId($this, $admin);
    }

    public function mount()
    {
        $this->supervisorAddService->mount($this);
    }

    public function submitForm()
    {
        $this->supervisorAddService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.supervisor.add-supervisor')
            ->extends('layouts.v1.app');
    }
}
