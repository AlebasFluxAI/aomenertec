<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Controllers\testFile;
use App\Http\Services\V1\Admin\User\Admin\AdminAddService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorAddService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorPriceConfigurationService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorPriceService;
use App\Http\Services\V1\Admin\User\Supervisor\SupervisorAddService;
use App\Models\Traits\AddUserFormTrait;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
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
