<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Services\V1\Admin\User\NetworkOperator\ClientvaupesStrataTypeConfigurationService;
use App\Models\Traits\ClientFormTrait;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;

class ClientVaupesStrataTypeConfig extends Component
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
    protected $listeners = ['somethingUpdated' => 'reloadComponent'];
    private $clientvaupesStrataTypeConfigurationService;

    public function __construct($id = null)
    {
        $this->clientvaupesStrataTypeConfigurationService = ClientvaupesStrataTypeConfigurationService::getInstance();
        parent::__construct($id);
    }


    public function reloadComponent($month, $year)
    {
        $this->render();
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.user.network-operator.price-configuration.vaupes-client-type-price-calculator',

        )->extends('layouts.v1.app');
    }

    public function changeVaupesFeeType($fee, $clientType, $month, $year)
    {
        $this->clientvaupesStrataTypeConfigurationService->changeVaupesFeeType($this, $fee, $clientType, $month, $year);
    }

    public function getVaupesFee($clientType, $month, $year)
    {
        return $this->clientvaupesStrataTypeConfigurationService->getVaupesFee($this, $clientType, $month, $year);
    }


}
