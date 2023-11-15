<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Models\V1\User;
use Livewire\Component;

class PriceNetworkOperatorWrap extends Component
{
    public function mount()
    {
        $this->model = User::getUserModel();
    }


    public function render()
    {
        return view('livewire.v1.admin.user.network-operator.price-configuration.price-network-operator-wrap')
            ->extends('layouts.v1.app');
    }
}
