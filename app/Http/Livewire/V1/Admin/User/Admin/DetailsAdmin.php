<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AdminDetailsService;
use App\Models\V1\Admin;
use Livewire\Component;

class DetailsAdmin extends Component
{
    public $admin;
    private $adminDetailService;


    public function __construct()
    {
        parent::__construct();
        $this->adminDetailService = AdminDetailsService::getInstance();
    }

    public function mount(Admin $admin)
    {
        $this->adminDetailService->mount($this, $admin);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.admin.detail-admin')
            ->extends('layouts.v1.app');
    }
}
