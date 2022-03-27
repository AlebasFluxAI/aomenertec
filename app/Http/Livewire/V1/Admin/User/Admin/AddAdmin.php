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
    public $style;
    protected $rules = [
        'identification' => 'required|min:6|unique:users,identification',
        'name' => 'required',
        'phone' => 'min:7',
        'email' => 'required|email|unique:users,email',
    ];
    private $superAdminAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->superAdminAddService = AdminAddService::getInstance();
    }

    public function submitForm()
    {
        $this->superAdminAddService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.admin.add-admin')
            ->extends('layouts.v1.app');
    }

}
