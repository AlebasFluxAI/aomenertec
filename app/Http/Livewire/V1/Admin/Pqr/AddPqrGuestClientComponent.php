<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentAlert;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class AddPqrGuestClientComponent extends Component
{
    private $addPqrGuestClientService;

    public function __construct($id = null)
    {
        $this->addPqrGuestClientService = AddPqrGuestClientService::getInstance();
        parent::__construct($id);
    }


    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.add-pqr-guest-client',
        )->extends('layouts.v1.app');
    }
}
