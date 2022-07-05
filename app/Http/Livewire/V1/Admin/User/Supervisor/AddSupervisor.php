<?php

namespace App\Http\Livewire\V1\Admin\User\Supervisor;

use App\Http\Services\V1\Admin\User\SuperAdmin\NetworkOperatorAddService;
use App\Http\Services\V1\Admin\User\Supervisor\SupervisorAddService;
use App\Models\Traits\AddUserFormTrait;
use App\Models\Traits\PassTrait;
use App\Models\Traits\ValidateUserFormTrait;
use Livewire\Component;

class AddSupervisor extends Component
{
    use ValidateUserFormTrait;
    use AddUserFormTrait;
    use PassTrait;


    public $message;
    public $picked;
    public $network_operators;
    public $network_operator_id;
    public $network_operator;


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
