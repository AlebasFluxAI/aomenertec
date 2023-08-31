<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\User\Admin\AdminIndexService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorIndexService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorPriceConfigurationService;
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

class PricePhotovoltaicConfig extends Component
{
    use WithPagination;
    use FilterTrait;
    use ClientFormTrait;

    public $model;
    public $months;
    public $years;
    public $month;
    public $year;
    public $date_picked;

    private $priceConfiguratioNetworkOperatorService;

    public function __construct($id = null)
    {
        $this->priceConfiguratioNetworkOperatorService = NetworkOperatorPriceConfigurationService::getInstance();
        parent::__construct($id);
    }

    public function mount(NetworkOperator $networkOperator)
    {
        return $this->priceConfiguratioNetworkOperatorService->mount($this, $networkOperator);
    }

    public function changeSubsidy($event, $stratum_id)
    {
        return $this->priceConfiguratioNetworkOperatorService->getSubsidy($this, $event, $stratum_id);
    }

    public function changeCredit($event, $stratum_id)
    {
        return $this->priceConfiguratioNetworkOperatorService->changeCredit($this, $event, $stratum_id);
    }

    public function changeValue($event, $stratum_id)
    {
        return $this->priceConfiguratioNetworkOperatorService->changeValue($this, $event, $stratum_id);
    }

    public function getSubsidy($stratum_id)
    {
        return $this->priceConfiguratioNetworkOperatorService->getSubsidy($this, $stratum_id);
    }

    public function getCredit($stratum_id)
    {
        return $this->priceConfiguratioNetworkOperatorService->getCredit($this, $stratum_id);
    }

    public function getValue($stratum_id)
    {
        return $this->priceConfiguratioNetworkOperatorService->getValue($this, $stratum_id);
    }

    public function pickDate()
    {
        $this->priceConfiguratioNetworkOperatorService->pickDate($this);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.user.network-operator.price-configuration.price-photovoltaic-configuration',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function changeVaupesFeeType($fee, $clientType, $month, $year, $client_type)
    {
        $this->priceConfiguratioNetworkOperatorService->changeVaupesFeeType($this, $fee, $clientType, $month, $year, ClientType::ZIN_PHOTOVOLTAIC);
    }

    public function getVaupesFee($clientType, $month, $year, $client_type)
    {
        return $this->priceConfiguratioNetworkOperatorService->getVaupesFee($this, $clientType, $month, $year, ClientType::ZIN_PHOTOVOLTAIC);
    }


    public function getData()
    {
        return $this->priceConfiguratioNetworkOperatorService->getData($this);
    }
}
