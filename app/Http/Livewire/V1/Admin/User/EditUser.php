<?php

namespace App\Http\Livewire\V1\Admin\User;

use App\Http\Services\V1\Admin\User\EditUserService;
use Livewire\Component;
use function view;

class EditUser extends Component
{
    public $messageP;
    public $identification;
    public $name;
    public $phone;
    public $email;
    public $role;
    public $roles = [];
    public $network_operators = [];
    public $network_operator_id;
    public $picked;
    public $network_operator;
    public $user;
    public $user_id;
    public $pickedU;
    public $messageU;
    public $users = [];
    protected $rules = [
        'network_operator' => 'required|min:2',
        'identification' => 'required|min:6',
        'name' => 'required|min:8',
        'phone' => 'min:7',
        'email' => 'required|email',
        'user' => 'required|min:2',
    ];
    private $editUserService;

    public function __construct()
    {
        parent::__construct();
        $this->editUserService = EditUserService::getInstance();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount()
    {
        $this->editUserService->mount($this);
    }

    public function updatedUser()
    {
        $this->editUserService->updatedUser($this);
    }

    public function assignUser($user)
    {
        $this->editUserService->assignUser($this, $user);
    }

    public function assignUserFirst()
    {
        $this->editUserService->assignUserFirst($this);
    }

    public function updatedNetworkOperator()
    {
        $this->editUserService->updatedNetworkOperator($this);
    }

    public function assignNetworkOperator($network_operator)
    {
        $this->editUserService->assignNetworkOperator($this, $network_operator);
    }

    public function assignNetworkOperatorFirst()
    {
        $this->editUserService->assignNetworkOperatorFirst($this);
    }

    public function edit()
    {
        $this->editUserService->edit($this);
    }

    public function delete()
    {
        $this->editUserService->delete($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.edit-user')
            ->extends('layouts.v1.app');
    }
}
