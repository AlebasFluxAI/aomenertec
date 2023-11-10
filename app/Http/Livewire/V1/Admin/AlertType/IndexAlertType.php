<?php

namespace App\Http\Livewire\V1\Admin\AlertType;

use App\Http\Services\V1\Admin\AlertType\AlertTypeIndexService;
use App\Models\V1\AlertType;
use App\Models\V1\EquipmentAlert;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class IndexAlertType extends Component
{
    use WithPagination;


    private $indexEquipmentService;

    public function __construct($id = null)
    {
        $this->indexEquipmentService = AlertTypeIndexService::getInstance();
        parent::__construct($id);
    }

    public function getEquipments()
    {
        return $this->indexEquipmentService->getEquipments();
    }

    public function edit($id)
    {
        $this->indexEquipmentService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->indexEquipmentService->delete($this, $id);
    }

    public function details($id)
    {
        $this->indexEquipmentService->details($this, $id);
    }

    public function render()
    {
        return view('livewire.administrar.v1.alertType.index-alert-type', [
            "alertTypes" => AlertType::pagination()
        ])->extends('layouts.v1.app');
    }
}
