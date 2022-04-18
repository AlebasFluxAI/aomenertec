<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AdminAddService;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddAdmin extends Component
{
    use WithFileUploads;

    public $password;
    public $identification;
    public $name;
    public $address;
    public $nit;
    public $last_name;
    public $phone;
    public $email;
    public $message;
    public $icon;
    public $styles;
    public $style;

    protected $rules = [
        'identification' => 'required|min:6|unique:users,identification',
        'name' => 'required',
        'phone' => 'min:7|unique:users,phone',
        'email' => 'required|email|unique:users,email',
    ];
    private $superAdminAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->superAdminAddService = AdminAddService::getInstance();
    }

    public function mount()
    {
        $this->superAdminAddService->mount($this);
    }

    public function submitForm()
    {
        $this->superAdminAddService->submitForm($this);
    }

    public function setStyle()
    {
        $this->superAdminAddService->setStyle($this);

    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.add-admin')
            ->extends('layouts.v1.app');
    }
}
