<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\NetworkOperator\TimelyPaymentService;
use App\Models\Traits\ClientFormTrait;
use App\Models\Traits\FilterTrait;
use App\Models\V1\NetworkOperator;
use Livewire\Component;
use Livewire\WithPagination;

class TimelyPaymentConfig extends Component
{
    use WithPagination;
    use FilterTrait;
    use ClientFormTrait;

    public $model;
    public $timely_payment_days;
    public $reconnection_cost;
    public $disconnection_days;


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

    public function submitForm()
    {
        return $this->timelyPayment->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.network-operator.price-configuration.timely-payment')
            ->extends('layouts.v1.app');
    }


}
