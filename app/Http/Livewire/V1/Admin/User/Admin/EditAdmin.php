<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AdminEditService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorEditService;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use Livewire\Component;

class EditAdmin extends Component
{
    public $model;
    public $name;
    public $last_name;
    public $phone;
    public $password;
    public $email;
    public $identification;
    private $editAdminService;

    public function __construct($id = null)
    {
        $this->editAdminService = AdminEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(Admin $admin)
    {
        $this->editAdminService->mount($this, $admin);
    }

    public function submitForm()
    {
        $this->editAdminService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.admin.edit-admin')
            ->extends('layouts.v1.app');
    }
}
