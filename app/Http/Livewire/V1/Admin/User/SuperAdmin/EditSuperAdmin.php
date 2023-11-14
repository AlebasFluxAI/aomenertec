<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin;

use App\Http\Services\V1\Admin\User\SuperAdmin\SuperAdminEditService;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class EditSuperAdmin extends Component
{
    public $model;
    protected $rules = [
        'model.identification' => 'required|min:6|unique:users,identification',
        'model.name' => 'required|min:6',
        'model.last_name' => 'required|min:6',
        'model.phone' => 'min:7|unique:users,phone',
        'model.email' => 'required|email|unique:users,email',
    ];
    private $superAdminEditService;

    public function __construct($id = null)
    {
        $this->superAdminEditService = SuperAdminEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(SuperAdmin $superAdmin)
    {
        $this->superAdminEditService->mount($this, $superAdmin);
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
