<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Services\V1\Admin\User\Technician\TechnicianAddService;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddTechnician extends Component
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
    public $network_operator;
    public $network_operator_id;

    protected $rules = [
        'identification' => 'required|min:6|unique:users,identification',
        'name' => 'required',
        'phone' => 'min:7',
        'email' => 'required|email|unique:users,email',
    ];
    private $superTechnicianAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->superTechnicianAddService = TechnicianAddService::getInstance();
    }

    public function assignNetworkOperator($network_operator)
    {
        $this->superTechnicianAddService->assignNetworkOperator($this, $network_operator);
    }

    public function updatedNetworkOperator()
    {
        $this->superTechnicianAddService->updatedNetworkOperator($this);
    }

    public function mount()
    {
        $this->superTechnicianAddService->mount($this);
    }

    public function submitForm()
    {
        $this->superTechnicianAddService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.technician.add-technician')
            ->extends('layouts.v1.app');
    }
}
