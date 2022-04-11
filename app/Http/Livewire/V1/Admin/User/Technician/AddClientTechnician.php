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
    public $clientsRelated;
    private $addTechnicianClient;

    public function __construct($id = null)
    {
        $this->addTechnicianClient = TechnicianAddClientService::getInstance();
        parent::__construct($id);
    }

    public function mount(Technician $technician)
    {
        $this->addTechnicianClient->mount($this, $technician);
    }


    public function addClient()
    {
        $this->addTechnicianClient->addClient($this);
    }

    public function updatedClient()
    {
        $this->addTechnicianClient->updatedClient($this);
    }

    public function assignClient($client)
    {
        $this->addTechnicianClient->assignClient($this, $client);
    }


    public function delete($client)
    {
        $this->addTechnicianClient->delete($this, $client["id"]);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.technician.add-client-technician')
            ->extends('layouts.v1.app');
    }
}
