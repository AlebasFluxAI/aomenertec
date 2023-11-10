<?php

namespace App\Http\Livewire\V1\Admin\User\Support;

use App\Http\Services\V1\Admin\User\Support\SupportAddService;
use App\Models\Traits\PassTrait;
use Livewire\Component;

class AddSupport extends Component
{
    use PassTrait;

    public $message;
    public $picked;
    public $network_operators;
    public $network_operator;
    public $network_operator_id;
    public $decodedAddress;
    public $latitude;
    public $longitude;
    public $form_title;
    public $model;
    public $indicatives;
    public $indicative;

    public $person_types;
    public $identification_types;
    public $admins;
    public $admin_id;
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
    ];
    private $superSupportAddService;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->superSupportAddService = SupportAddService::getInstance();
    }

    public function assignNetworkOperator($network_operator)
    {
        $this->superSupportAddService->assignNetworkOperator($this, $network_operator);
    }

    public function updated($propertyName)
    {
        $this->superSupportAddService->updated($this, $propertyName);
    }

    public function updatedNetworkOperator()
    {
        $this->superSupportAddService->updatedNetworkOperator($this);
    }

    public function mount()
    {
        $this->superSupportAddService->mount($this);
    }

    public function submitForm()
    {
        $this->superSupportAddService->submitForm($this);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.support.add-support')
            ->extends('layouts.v1.app');
    }
}
