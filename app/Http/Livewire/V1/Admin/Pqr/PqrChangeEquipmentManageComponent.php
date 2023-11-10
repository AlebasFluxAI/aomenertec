<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Http\Services\V1\Admin\Pqr\PqrChangeEquipmentManageService;
use App\Models\Traits\TableRowCheckTrait;
use App\Models\V1\Pqr;
use Livewire\Component;
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
