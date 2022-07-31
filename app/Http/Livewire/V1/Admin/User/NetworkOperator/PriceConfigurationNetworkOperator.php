<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\User\Admin\AdminIndexService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorIndexService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorPriceConfigurationService;
use App\Models\Traits\FilterTrait;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\Admin;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use Livewire\Component;
use Livewire\WithPagination;

class PriceConfigurationNetworkOperator extends Component
{
    use WithPagination;
    use FilterTrait;

    public $model;

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

    public function render()
    {
        return view(
            'livewire.v1.admin.user.network-operator.price-configuration-network-operator',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->priceConfiguratioNetworkOperatorService->getData($this);
    }
}
