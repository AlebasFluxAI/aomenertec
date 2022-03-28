<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Services\V1\Admin\User\Technician\TechnicianAddClientService;
use App\Http\Services\V1\Admin\User\Technician\TechnicianAddService;
use App\Http\Services\V1\Admin\User\Technician\TechnicianEditService;
use App\Models\V1\Technician;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddClientTechnician extends Component
{
    public $model;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $identification;
    public $clients;
    public $client;
    public $client_picked;
    public $client_id;
    public $message_client;
    private $editTechnicianService;

    public function __construct($id = null)
    {
        $this->editTechnicianService = TechnicianAddClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Technician $technician)
    {
        $this->editTechnicianService->mount($this, $technician);
    }

    public function addClient()
    {
        $this->editTechnicianService->addClient($this);
    }

    public function updatedClient()
    {
        $this->editTechnicianService->updatedClient($this);
    }

    public function assignClient($client)
    {
        $this->editTechnicianService->assignClient($this, $client);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.technician.add-client-technician')
            ->extends('layouts.v1.app');
    }
}
