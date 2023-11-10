<?php

namespace App\Http\Livewire\V1\Admin\User;

use App\Http\Services\V1\Admin\User\SelectRoleUserService;
use Livewire\Component;
use function view;

class SelectRoleUser extends Component
{

    public $roles;
    private $selectRoleUserService;

    public function __construct()
    {
        parent::__construct();
        $this->selectRoleUserService = SelectRoleUserService::getInstance();
    }

    public function mount()
    {
        $this->selectRoleUserService->mount($this);
    }

    public function selectRole($role)
    {
        $this->selectRoleUserService->selectRole($this, $role);
    }

    public function render()
    {


        return view("livewire.v1.admin.user.select-role")
            ->extends('layouts.v1.app');
    }
}
