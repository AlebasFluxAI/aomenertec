<?php

namespace App\Http\Livewire\V1\Admin\User\Support;

use App\Http\Services\V1\Admin\User\Support\SupportAddService;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddSupport extends Component
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
    private $superSupportAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->superSupportAddService = SupportAddService::getInstance();
    }

    public function assignNetworkOperator($network_operator)
    {
        $this->superSupportAddService->assignNetworkOperator($this, $network_operator);
    }

    public function updatedNetworkOperator()
    {
        $this->superSupportAddService->updatedNetworkOperator($this);
    }

    public function mount()
    {
        $this->superSupportAddService->mount($this);
    }

    public function submitForm()
    {
        $this->superSupportAddService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.support.add-support')
            ->extends('layouts.v1.app');
    }
}
