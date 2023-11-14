<?php

namespace App\Http\Livewire\V1\Admin\User\Technician;

use App\Http\Services\V1\Admin\User\Technician\TechnicianEditService;
use App\Models\V1\Technician;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditTechnician extends Component
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
        'model.indicative' => '',

    ];
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

    public function updatedModel($value, $key)
    {
        $this->editTechnicianService->updatedModel($this, $value, $key);
    }

    public function updatedLatitude()
    {
        $this->editTechnicianService->updatedLatitude($this);
    }

    public function updatedLongitude()
    {
        $this->editTechnicianService->updatedLongitude($this);
    }

    public function updated($propertyName)
    {
        $this->editTechnicianService->updated($this, $propertyName);
    }

    public function submitForm()
    {
        $this->editTechnicianService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.technician.edit-technician')
            ->extends('layouts.v1.app');
    }
}
