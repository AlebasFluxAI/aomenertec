<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AdminEditService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorEditService;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditAdmin extends Component
{
    use WithFileUploads;

    public $model;
    public $name;
    public $last_name;
    public $phone;
    public $address;
    public $nit;
    public $icon;
    public $password;
    public $email;
    public $identification;
    public $style;
    public $styles;
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

    public function setStyle()
    {
        $this->editAdminService->setStyle($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.edit-admin')
            ->extends('layouts.v1.app');
    }
}
