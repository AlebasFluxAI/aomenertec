<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\User\Admin\AdminIndexService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorIndexService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorPriceConfigurationService;
use App\Http\Services\V1\Admin\User\NetworkOperator\TimelyPaymentService;
use App\Models\Traits\ClientFormTrait;
use App\Models\Traits\FilterTrait;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\Admin;
use App\Models\V1\ClientType;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use Livewire\Component;
use Livewire\WithPagination;

class TimelyPaymentConfig extends Component
{
    use WithPagination;
    use FilterTrait;
    use ClientFormTrait;

    public $model;
    public $timelyPaymentDays;
    public $reconnectionCost;
    public $disconnectionDays;
    

    private $timelyPayment;

    public function __construct($id = null)
    {
        $this->timelyPayment = TimelyPaymentService::getInstance();
        parent::__construct($id);
    }

    public function mount(NetworkOperator $networkOperator)
    {
        return $this->timelyPayment->mount($this, $networkOperator);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.network-operator.price-configuration.timely-payment')->extends('layouts.v1.app');
    }


}
