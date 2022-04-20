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
    public $network_operators;
    public $network_operator_id;
    public $network_operator;


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


    public function assignNetworkOperator($network_operator)
    {
        $this->supervisorAddService->assignNetworkOperator($this, $network_operator);
    }

    public function updatedNetworkOperator()
    {
        $this->supervisorAddService->updatedNetworkOperator($this);
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
