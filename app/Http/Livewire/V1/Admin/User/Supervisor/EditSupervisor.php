<?php

namespace App\Http\Livewire\V1\Admin\User\Supervisor;

use App\Http\Services\V1\Admin\User\Supervisor\SupervisorEditService;
use App\Models\V1\Supervisor;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditSupervisor extends Component
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
    private $supervisorEditService;

    public function __construct($id = null)
    {
        $this->supervisorEditService = SupervisorEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(Supervisor $supervisor)
    {
        $this->supervisorEditService->mount($this, $supervisor);
    }

    public function updatedModel($value, $key)
    {
        $this->supervisorEditService->updatedModel($this, $value, $key);
    }

    public function updatedLatitude()
    {
        $this->supervisorEditService->updatedLatitude($this);
    }

    public function updatedLongitude()
    {
        $this->supervisorEditService->updatedLongitude($this);
    }

    public function updated($propertyName)
    {
        $this->supervisorEditService->updated($this, $propertyName);
    }

    public function submitForm()
    {
        $this->supervisorEditService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.supervisor.edit-supervisor')
            ->extends('layouts.v1.app');
    }
}
