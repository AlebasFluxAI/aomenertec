<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Controllers\testFile;
use App\Http\Services\V1\Admin\User\Admin\AdminAddService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorAddService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorPriceService;
use App\Http\Services\V1\Admin\User\Supervisor\SupervisorAddService;
use App\Models\Traits\AddUserFormTrait;
use App\Models\Traits\ValidateUserFormTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PriceClientTypePriceNetworkOperator extends Component
{

    public $model;
    public $taxType = [];

    private $networkOperatorPriceService;
    public $months;
    public $years;
    public $month;
    public $year;
    public $date_picked;
    public $client_type;


    function __construct($id = null)
    {
        parent::__construct($id);
        $this->networkOperatorPriceService = NetworkOperatorPriceService::getInstance();
    }

    public function mount($client_type)
    {
        $this->networkOperatorPriceService->mount($this, $client_type);
    }

    public function getFee($value, $level, $type)
    {
        return $this->networkOperatorPriceService->getFee($this, $value, $level, $type);

    }

    public function changeFee($value, $level, $type, $client_type)
    {
        $this->networkOperatorPriceService->changeFee($this, $value, $level, $type, $client_type);

    }

    public function changeOptionalFee($value, $level, $type, $client_type)
    {
        $this->networkOperatorPriceService->changeOptionalFee($this, $value, $level, $type, $client_type);

    }

    public function getOptionalFee($value, $level, $type)
    {
        return $this->networkOperatorPriceService->getOptionalFee($this, $value, $level, $type);

    }

    public function changeOtherFee($value, $type, $strata, $client_type)
    {
        $this->networkOperatorPriceService->changeOtherFee($this, $type, $value, $strata, $client_type);

    }

    public function changeTaxTypeStrata($value, $strata, $client_type)
    {
        $this->networkOperatorPriceService->changeTaxTypeStrata($this, $value, $strata, $client_type);
    }

    public function getPercentageOption($strata, $clientType)
    {
        return $this->networkOperatorPriceService->getPercentageOption($this, $strata, $clientType);
    }

    public function getOtherFee($value, $strata, $client_type)
    {

        return $this->networkOperatorPriceService->getOtherFee($this, $value, $strata, $client_type);
    }


    public function changeSubsidy($event, $stratum_id)
    {
        return $this->networkOperatorPriceService->changeSubsidy($this, $event, $stratum_id);
    }

    public function changeCredit($event, $stratum_id)
    {
        return $this->networkOperatorPriceService->changeCredit($this, $event, $stratum_id);
    }

    public function changeValue($event, $stratum_id)
    {
        return $this->networkOperatorPriceService->changeValue($this, $event, $stratum_id);
    }

    public function getSubsidy($stratum_id)
    {
        return $this->networkOperatorPriceService->getSubsidy($this, $stratum_id);
    }

    public function getCredit($stratum_id)
    {
        return $this->networkOperatorPriceService->getCredit($this, $stratum_id);
    }

    public function pickDate()
    {
        $this->networkOperatorPriceService->pickDate($this);
    }


    public function getValue($stratum_id)
    {
        return $this->networkOperatorPriceService->getValue($this, $stratum_id);
    }

    public function render()
    {
        return view('livewire.v1.admin.user.network-operator.price-configuration.client-type-price-calculator')
            ->extends('layouts.v1.app');
    }
}
