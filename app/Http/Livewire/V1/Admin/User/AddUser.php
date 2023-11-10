<?php

namespace App\Http\Livewire\V1\Admin\User;

use App\Http\Services\V1\Admin\User\AddUserService;
use Livewire\Component;
use function view;

class AddUser extends Component
{
    public $password;
    public $identification;
    public $name;
    public $phone;
    public $email;
    public $role;
    public $roles;
    public $network_operators = [];
    public $network_operator_id;
    public $picked;
    public $message;
    public $network_operator;
    protected $rules = [
        'network_operator' => 'required|min:2',
        'identification' => 'required|min:6|unique:users,identification',
        'name' => 'required|min:8',
        'phone' => 'min:7',
        'email' => 'required|email|unique:users,email',
        'role' => 'required',
    ];
    private $addUserService;

    public function __construct()
    {
        parent::__construct();
        $this->addUserService = AddUserService::getInstance();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount()
    {
        $this->addUserService->mount($this);
    }

    public function updatedNetworkOperator()
    {
        $this->addUserService->updatedNetworkOperator($this);
    }

    public function save()
    {
        $this->addUserService->save($this);
        $this->resetExcept('roles');
    }

    public function render()
    {
        return view('livewire.v1.admin.user.add-user')
            ->extends('layouts.v1.app');
    }
}
