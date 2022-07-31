<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin;

use App\Http\Services\V1\Admin\User\Admin\AdminAddService;
use App\Http\Services\V1\Admin\User\SuperAdmin\SuperAdminAddService;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class AddSuperAdmin extends Component
{
    public $model;
    public $message;

    private $superAdminAddService;

    protected $rules = [
        'model.identification' => 'required|min:6|unique:users,identification',
        'model.name' => 'required|min:6',
        'model.last_name' => 'required|min:6',
        'model.phone' => 'min:7|unique:users,phone',
        'model.email' => 'required|email|unique:users,email',
    ];

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->superAdminAddService = SuperAdminAddService::getInstance();
    }

    public function updated($propertyName)
    {
        $this->superAdminAddService->updated($this, $propertyName);
    }

    public function submitForm()
    {
        $this->superAdminAddService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.super.add-super-admin')
            ->extends('layouts.v1.app');
    }
}
