<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorEditService;
use App\Models\V1\NetworkOperator;
use Livewire\Component;

class EditNetworkOperator extends Component
{
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
        'model.indicative' => '',
    ];
    private $editNetworkOperatorService;

    public function __construct($id = null)
    {
        $this->editNetworkOperatorService = NetworkOperatorEditService::getInstance();
        parent::__construct($id);
    }

    public function mount(NetworkOperator $networkOperator)
    {
        $this->editNetworkOperatorService->mount($this, $networkOperator);
    }

    public function updatedModel($value, $key)
    {
        $this->editNetworkOperatorService->updatedModel($this, $value, $key);
    }

    public function updatedLatitude()
    {
        $this->editNetworkOperatorService->updatedLatitude($this);
    }

    public function updatedLongitude()
    {
        $this->editNetworkOperatorService->updatedLongitude($this);
    }

    public function updated($propertyName)
    {
        $this->editNetworkOperatorService->updated($this, $propertyName);
    }

    public function submitForm()
    {
        $this->editNetworkOperatorService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.network-operator.edit-network-operator')
            ->extends('layouts.v1.app');
    }
}
