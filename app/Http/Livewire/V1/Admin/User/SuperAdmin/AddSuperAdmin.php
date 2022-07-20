<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin;

use App\Http\Services\V1\Admin\User\Admin\AdminAddService;
use App\Http\Services\V1\Admin\User\SuperAdmin\SuperAdminAddService;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class AddSuperAdmin extends Component
{
    use ValidateUserFormTrait;

    public $model;
    public $message;

    private $superAdminAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->superAdminAddService = SuperAdminAddService::getInstance();
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
