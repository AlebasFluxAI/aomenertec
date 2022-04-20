<?php

namespace App\Http\Livewire\V1\Admin\User\Admin;

use App\Http\Services\V1\Admin\User\Admin\AdminDetailsService;
use App\Models\V1\Admin;
use Livewire\Component;

class DetailsAdmin extends Component
{
    public $model;
    private $detailsNetworkOperatorService;


    public function __construct($id = null)
    {
        $this->detailsNetworkOperatorService = AdminDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(Admin $admin)
    {
        $this->detailsNetworkOperatorService->mount($this, $admin);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.admin.detail-admin')
            ->extends('layouts.v1.app');
    }
}
