<?php

namespace App\Http\Livewire\V1\Admin\User\SuperAdmin;

use App\Http\Services\V1\Admin\User\SuperAdmin\SuperAdminDetailsService;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class DetailsSuperAdmin extends Component
{


    public $model;
    private $superAdminDetailService;


    public function __construct($id = null)
    {
        $this->superAdminDetailService = SuperAdminDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(SuperAdmin $superAdmin)
    {
        $this->superAdminDetailService->mount($this, $superAdmin);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.super.detail-super-admin')
            ->extends('layouts.v1.app');
    }
}
