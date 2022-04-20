<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorDetailsService;
use App\Models\V1\NetworkOperator;
use Livewire\Component;

class DetailsNetworkOperator extends Component
{
    public $model;
    private $detailsNetworkOperatorService;


    public function __construct($id = null)
    {
        $this->detailsNetworkOperatorService = NetworkOperatorDetailsService::getInstance();
        parent::__construct($id);
    }

    public function mount(NetworkOperator $networkOperator)
    {
        $this->detailsNetworkOperatorService->mount($this, $networkOperator);
    }


    public function render()
    {
        return view('livewire.v1.admin.user.network-operator.detail-network-operator')
            ->extends('layouts.v1.app');
    }
}
