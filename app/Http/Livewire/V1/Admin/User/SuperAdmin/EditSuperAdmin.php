<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin;

use App\Http\Services\V1\Admin\User\SuperAdmin\SuperAdminEditService;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\Equipment;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class EditSuperAdmin extends Component
{

    public $model;
    private $superAdminEditService;

    protected $rules = [
        'model.identification' => 'required|min:6|unique:users,identification',
        'model.name' => 'required|min:6',
        'model.last_name' => 'required|min:6',
        'model.phone' => 'min:7|unique:users,phone',
        'model.email' => 'required|email|unique:users,email',
    ];

    public function __construct($id = null)
    {
        $this->superAdminEditService = SuperAdminEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(SuperAdmin $superAdmin)
    {
        $this->superAdminEditService->mount($this, $superAdmin);
    }

    public function updated($propertyName)
    {
        $this->superAdminEditService->updated($this, $propertyName);
    }

    public function submitForm()
    {
        $this->superAdminEditService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.super.edit-super-admin')
            ->extends('layouts.v1.app');
    }
}
