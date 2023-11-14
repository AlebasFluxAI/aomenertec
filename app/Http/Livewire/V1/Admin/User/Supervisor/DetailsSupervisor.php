<?php

namespace App\Http\Livewire\V1\Admin\User\Supervisor;

use App\Http\Services\V1\Admin\User\SuperAdmin\NetworkOperatorDetailsService;
use App\Http\Services\V1\Admin\User\Supervisor\SupervisorDetailsService;
use App\Models\V1\Supervisor;
use Livewire\Component;

class DetailsSupervisor extends Component
{
    public $model;
    private $supervisorDetailService;


    public function __construct($id = null)
    {
        $this->supervisorDetailService = SupervisorDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(Supervisor $supervisor)
    {
        $this->supervisorDetailService->mount($this, $supervisor);
    }

    public function conditionalMonitoring($id)
    {
        return $this->supervisorDetailService->conditionalMonitoring($this, $id);
    }

    public function conditionalDeleteClient($id)
    {
        return $this->supervisorDetailService->conditionalDeleteClient($this, $id);
    }

    public function deleteClient($id)
    {
        $this->supervisorDetailService->deleteclient($this, $id);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.supervisor.detail-supervisor')
            ->extends('layouts.v1.app');
    }
}
