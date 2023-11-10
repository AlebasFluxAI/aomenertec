<?php

namespace App\Http\Livewire\V1\Admin\Pqr;

use App\Http\Services\V1\Admin\Pqr\PqrIndexService;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class PqrIndexComponent extends Component
{
    use WithPagination;

    private $pqrIndexService;

    public function __construct($id = null)
    {
        $this->pqrIndexService = PqrIndexService::getInstance();
        parent::__construct($id);
    }

    public function changeLevel($id)
    {
        return $this->pqrIndexService->changeLevel($this, $id);
    }

    public function equipmentNotRequest($id)
    {
        return $this->pqrIndexService->equipmentNotRequest($this, $id);
    }

    public function equipmentRequest($id)
    {
        return $this->pqrIndexService->equipmentRequest($this, $id);
    }

    public function linkClientConditional()
    {
        return $this->pqrIndexService->linkClientConditional($this);
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

    public function canConvertToOrder($id)
    {
        return $this->pqrIndexService->canConvertToOrder($this, $id);

    }

    public function downloadReport($id)
    {
        return $this->pqrIndexService->downloadReport($this, $id);

    }

    public function canDownloadReport($id)
    {
        return $this->pqrIndexService->canDownloadReport($this, $id);

    }

    public function convertToWorkOrder($id)
    {
        return $this->pqrIndexService->convertToWorkOrder($this, $id);

    }

    public function render()
    {
        return view(
            'livewire.v1.admin.pqr.index-pqr',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->pqrIndexService->getData($this);
    }
}
