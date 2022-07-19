<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Http\Services\V1\Admin\EquipmentAlert\EquipmentAlertIndexService;
use App\Http\Services\V1\Admin\EquipmentType\EquipmentTypeIndexService;
use App\Http\Services\V1\Admin\Pqr\AddPqrGuestClientService;
use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
use App\Models\Traits\PassTrait;
use App\Models\V1\AlertType;
use App\Models\V1\Equipment;

use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use function view;

class PqrIndexComponent extends Component
{

    private $pqrIndexService;
    use WithPagination;

    public function __construct($id = null)
    {
        $this->pqrIndexService = PqrIndexService::getInstance();
        parent::__construct($id);
    }

    public function changeLevel($id)
    {
        return $this->pqrIndexService->changeLevel($this, $id);
    }

    public function details($id)
    {
        $this->pqrIndexService->details($this, $id);
    }

    public function equipmentNotRequest($id)
    {
        return $this->pqrIndexService->equipmentNotRequest($this, $id);

    }

    public function equipmentRequest($id)
    {
        return !($this->pqrIndexService->equipmentNotRequest($this, $id));

    }


    public function closePqr($id)
    {
        return $this->pqrIndexService->closePqr($this, $id);

    }

    public function requestEquipment($id)
    {
        $this->pqrIndexService->requestEquipment($this, $id);

    }

    public function openTicked($id)
    {
        return $this->pqrIndexService->openTicked($this, $id);
    }

    public function closedTicked($id)
    {
        return $this->pqrIndexService->closedTicked($this, $id);

    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.index-pqr', [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->pqrIndexService->getData($this);
    }
}
