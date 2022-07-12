<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Services\V1\Admin\User\Technician\TechnicianEditService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorEditService;
use App\Models\Traits\AddUserFormTrait;
use App\Models\V1\Technician;
use App\Models\V1\NetworkOperator;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditTechnician extends Component
{
    use AddUserFormTrait;

    public $model;
    public $name;
    public $last_name;
    public $phone;
    public $email;
    public $identification;
    public $clients;
    public $client;
    public $client_picked;
    public $message_client;
    private $editTechnicianService;

    public function __construct($id = null)
    {
        $this->editTechnicianService = TechnicianEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(Technician $technician)
    {
        $this->editTechnicianService->mount($this, $technician);
    }

    public function submitForm()
    {
        $this->editTechnicianService->submitForm($this);
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
        return view('livewire.v1.admin.user.technician.edit-technician')
            ->extends('layouts.v1.app');
    }
}
