<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\Admin\AdminAddService;
use Livewire\Component;

class AddNetworkOperator extends Component
{
    public $password;
    public $identification;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $message;
    private $superAdminAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->superAdminAddService = AdminAddService::getInstance();
    }

    public function render()
    {
        return view('livewire.v1.admin.user.admin.add-admin')
            ->extends('layouts.v1.app');
    }

}
