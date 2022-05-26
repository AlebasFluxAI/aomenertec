<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\AddClientService;
use App\Http\Services\V1\Admin\Client\AddClientTechnicianService;
use App\Models\V1\Client;
use Livewire\Component;

class AddClientTechnician extends Component
{
    public $technicians;
    public $model;
    public $technicianId;
    public $technician_related;
    public $technician_picked;
    public $message_technician;
    private $addClientTechnicianService;


    public function __construct()
    {
        parent::__construct();
        $this->addClientTechnicianService = AddClientTechnicianService::getInstance();
    }

    public function pass()
    {
    }

    public function mount(Client $client)
    {
        $this->addClientTechnicianService->mount($this, $client);
    }

    public function relateTechnician()
    {
        $this->addClientTechnicianService->relateTechnician($this);
    }

    public function delete($technicianId)
    {
        $this->addClientTechnicianService->delete($this, $technicianId);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.add-client-technician')
            ->extends('layouts.v1.app');
    }
}
