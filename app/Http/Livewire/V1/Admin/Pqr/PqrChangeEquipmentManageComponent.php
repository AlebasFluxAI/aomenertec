<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\PqrChangeEquipmentManageService;
use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
use App\Models\Traits\PassTrait;
use App\Models\Traits\TableRowCheckTrait;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use App\Models\V1\Pqr;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;

class PqrChangeEquipmentManageComponent extends Component
{
    use TableRowCheckTrait;
    use WithPagination;

    public $equipmentToChange;
    private $pqrChangeEquipmentManageService;

    public function __construct($id = null)
    {
        $this->pqrChangeEquipmentManageService = PqrChangeEquipmentManageService::getInstance();
        parent::__construct($id);
    }

    public function mount(Pqr $pqr)
    {
        $this->pqrChangeEquipmentManageService->mount($this, $pqr);
    }

    public function updatedSelectedRows()
    {
        $this->pqrChangeEquipmentManageService->updatedSelectedRows($this);
    }


    public function equipmentByType($equipmentType)
    {
        return $this->pqrChangeEquipmentManageService->equipmentByType($this, $equipmentType);
    }


    public function confirmEquipmentChange($equipmentId)
    {
        $this->pqrChangeEquipmentManageService->confirmEquipmentChange($this, $equipmentId);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.change-equipment-manage-pqr',
        )->extends('layouts.v1.app');
    }
}
