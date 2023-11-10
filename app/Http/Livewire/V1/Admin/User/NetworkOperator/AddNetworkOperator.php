<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorAddService;
use Livewire\Component;

class AddNetworkOperator extends Component
{
    public $decodedAddress;
    public $latitude;
    public $longitude;
    public $form_title;
    public $model;
    public $message;
    public $person_types;
    public $identification_types;
    public $admins;
    public $indicatives;
    public $indicative;
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
        'model.admin_id' => 'required',
    ];
    private $networkOperatorAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->networkOperatorAddService = NetworkOperatorAddService::getInstance();
    }

    public function mount()
    {
        $this->networkOperatorAddService->mount($this);
    }

    public function updatedModel($value, $key)
    {
        $this->networkOperatorAddService->updatedModel($this, $value, $key);
    }

    public function updated($propertyName)
    {
        $this->networkOperatorAddService->updated($this, $propertyName);
    }

    public function updatedLatitude()
    {
        $this->networkOperatorAddService->updatedLatitude($this);
    }

    public function updatedLongitude()
    {
        $this->networkOperatorAddService->updatedLongitude($this);
    }

    public function submitForm()
    {
        $this->networkOperatorAddService->submitForm($this);
    }

    public function updatedAdminId()
    {
        $this->networkOperatorAddService->updatedAdminId($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.network-operator.add-network-operator')
            ->extends('layouts.v1.app');
    }
}
