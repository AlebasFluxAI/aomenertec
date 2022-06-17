<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorEditService;
use App\Models\Traits\AddUserFormTrait;
use App\Models\V1\NetworkOperator;
use Livewire\Component;

class EditNetworkOperator extends Component
{
    use AddUserFormTrait;

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
