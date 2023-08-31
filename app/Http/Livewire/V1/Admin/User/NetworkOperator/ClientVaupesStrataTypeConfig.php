<?php

namespace App\Http\Livewire\V1\Admin\User\NetworkOperator;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\User\Admin\AdminIndexService;
use App\Http\Services\V1\Admin\User\NetworkOperator\ClientvaupesStrataTypeConfigurationService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorIndexService;
use App\Http\Services\V1\Admin\User\NetworkOperator\NetworkOperatorPriceConfigurationService;
use App\Models\Traits\ClientFormTrait;
use App\Models\Traits\FilterTrait;
use App\Models\Traits\ValidateUserFormTrait;
use App\Models\V1\Admin;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
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

    private $clientvaupesStrataTypeConfigurationService;

    protected $listeners = ['somethingUpdated' => 'reloadComponent'];


    public function __construct($id = null)
    {
        $this->clientvaupesStrataTypeConfigurationService = ClientvaupesStrataTypeConfigurationService::getInstance();
        parent::__construct($id);
    }


    public function reloadComponent($month, $year)
    {
        $this->render();
    }


    public function changeVaupesFeeType($fee, $clientType, $month, $year)
    {
        $this->clientvaupesStrataTypeConfigurationService->changeVaupesFeeType($this, $fee, $clientType, $month, $year);
    }

    public function getVaupesFee($clientType, $month, $year)
    {
        return $this->clientvaupesStrataTypeConfigurationService->getVaupesFee($this, $clientType, $month, $year);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.user.network-operator.price-configuration.vaupes-client-type-price-calculator',

        )->extends('layouts.v1.app');
    }


}
