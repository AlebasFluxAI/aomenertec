<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorServiceBagConfigurationService;
use App\Models\Traits\FilterTrait;
use App\Models\V1\NetworkOperator;
use Livewire\Component;
use Livewire\WithPagination;

class BagServiceConfigurationNetworkOperator extends Component
{
    use WithPagination;
    use FilterTrait;

    public $model;
    public $prices;
    public $currency;
    public $pqr_bag;
    public $work_order_hours;
    public $billing_day;
    public $has_billable_pqr;
    public $has_billable_clients;
    public $has_billable_orders;
    public $pqr_price;
    public $orders_price;
    public $initial_package_pqr_price;
    public $initial_package_orders_price;
    public $currencies;
    public $client_types;
    public $prices_zni_fotovoltaico;
    public $zni_conventional;
    public $zni_rural;
    public $sin_conventional;
    public $monitoring;
    public $min_clients;
    public $min_client_value;
    protected $rules = [
        'prices.*.value' => 'required',
    ];
    private $networkOperatorServiceBagConfigurationService;

    public function __construct($id = null)
    {
        $this->networkOperatorServiceBagConfigurationService = NetworkOperatorServiceBagConfigurationService::getInstance();
        parent::__construct($id);
    }

    public function mount(NetworkOperator $networkOperator)
    {
        return $this->networkOperatorServiceBagConfigurationService->mount($this, $networkOperator);
    }

    public function updatedHasBillablePqr()
    {
        $this->networkOperatorServiceBagConfigurationService->updatedHasBillablePqr($this);

    }

    public function updatedHasBillableOrders()
    {
        $this->networkOperatorServiceBagConfigurationService->updatedHasBillableOrders($this);

    }


    public function updatedHasBillableClients()
    {
        $this->networkOperatorServiceBagConfigurationService->updatedHasBillableClients($this);

    }

    public function submitForm()
    {
        return $this->networkOperatorServiceBagConfigurationService->submitForm($this);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.user.network-operator.bag-service-configuration-network-operator'
        )->extends('layouts.v1.app');
    }

}
