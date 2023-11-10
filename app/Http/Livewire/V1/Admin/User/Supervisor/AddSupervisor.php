<?php

namespace App\Http\Livewire\V1\Admin\User\Supervisor;

use App\Http\Services\V1\Admin\User\SuperAdmin\NetworkOperatorAddService;
use App\Http\Services\V1\Admin\User\Supervisor\SupervisorAddService;
use App\Models\Traits\PassTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddSupervisor extends Component
{
    use PassTrait;
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
        'model.name' => 'required|min:2',
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
    private $supervisorAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->supervisorAddService = SupervisorAddService::getInstance();
    }

    public function mount()
    {
        $this->supervisorAddService->mount($this);
    }

    public function updatedModel($value, $key)
    {
        $this->supervisorAddService->updatedModel($this, $value, $key);
    }

    public function updated($propertyName)
    {
        $this->supervisorAddService->updated($this, $propertyName);
    }

    public function updatedLatitude()
    {
        $this->supervisorAddService->updatedLatitude($this);
    }

    public function updatedLongitude()
    {
        $this->supervisorAddService->updatedLongitude($this);
    }

    public function updatedAdminId()
    {
        $this->supervisorAddService->updatedAdminId($this);
    }

    public function submitForm()
    {
        $this->supervisorAddService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.supervisor.add-supervisor')
            ->extends('layouts.v1.app');
    }
}
