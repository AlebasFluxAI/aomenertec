<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Services\V1\Admin\User\Technician\TechnicianDetailsService;
use App\Models\V1\Technician;
use Livewire\Component;

class DetailsTechnician extends Component
{
    public $model;
    private $detailsNetworkOperatorService;


    public function __construct($id = null)
    {
        $this->detailsNetworkOperatorService = TechnicianDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(Technician $technician)
    {
        $this->detailsNetworkOperatorService->mount($this, $technician);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.technician.detail-technician')
            ->extends('layouts.v1.app');
    }
}
