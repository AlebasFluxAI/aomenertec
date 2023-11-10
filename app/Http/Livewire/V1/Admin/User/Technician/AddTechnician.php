<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Services\V1\Admin\User\Technician\TechnicianAddService;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddTechnician extends Component
{
    use WithFileUploads;

    public $decodedAddress;
    public $latitude;
    public $longitude;
    public $form_title;
    public $model;
    public $message;
    public $person_types;
    public $identification_types;
    public $admins;
    public $admin_id;
    public $network_operators;
    public $indicatives;
    public $indicative;
    public $sign;
    protected $rules = [
        'model.identification' => 'required|min:6|unique:users,identification',
        'model.name' => 'required|min:6',
        'model.last_name' => 'required|min:6',
        'model.phone' => 'min:7|unique:users,phone',
        'model.email' => 'required|email|unique:users,email',
        'model.address_details' => 'required',
        'model.latitude' => 'required',
        'model.longitude' => 'required',
        'model.billing_name' => 'required',
        'model.billing_address' => 'required',
        'model.person_type' => 'required',
        'model.identification_type' => 'required',
        'model.network_operator_id' => 'required',
    ];
    private $technicianAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->technicianAddService = TechnicianAddService::getInstance();
    }

    public function pass()
    {
    }


    public function mount()
    {
        $this->technicianAddService->mount($this);
    }

    public function updatedModel($value, $key)
    {
        $this->technicianAddService->updatedModel($this, $value, $key);
    }

    public function updated($propertyName)
    {
        $this->technicianAddService->updated($this, $propertyName);
    }

    public function updatedLatitude()
    {
        $this->technicianAddService->updatedLatitude($this);
    }

    public function updatedLongitude()
    {
        $this->technicianAddService->updatedLongitude($this);
    }

    public function updatedAdminId()
    {
        $this->technicianAddService->updatedAdminId($this);
    }

    public function submitForm()
    {
        $this->technicianAddService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.technician.add-technician')
            ->extends('layouts.v1.app');
    }
}
