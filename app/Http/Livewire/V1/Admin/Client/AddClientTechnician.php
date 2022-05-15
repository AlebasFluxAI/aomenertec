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
    public $technician_id;
    public $technician_related;
    private $addClientTechnicianService;


    public function __construct()
    {
        parent::__construct();
        $this->addClientTechnicianService = AddClientTechnicianService::getInstance();
    }


    public function mount(Client $client)
    {
        $this->addClientTechnicianService->mount($this, $client);
    }


    public function render()
    {
        return view('livewire.v1.admin.client.add-client-technician')
            ->extends('layouts.v1.app');
    }
}
