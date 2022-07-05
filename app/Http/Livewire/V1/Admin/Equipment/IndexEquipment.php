<?php

namespace App\Http\Livewire\V1\Admin\Equipment;

use App\Events\ChatEvent;
use App\Http\Services\V1\Admin\Equipment\EquipmentAddService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Models\Traits\FilterTrait;
use App\Models\Traits\MenuTrait;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use Livewire\Component;
use Livewire\WithPagination;
use PhpMqtt\Client\Facades\MQTT;
use function view;

class IndexEquipment extends Component
{
    use WithPagination;
    use FilterTrait;

    private $indexEquipmentService;

    public function __construct($id = null)
    {
        $this->indexEquipmentService = EquipmentIndexService::getInstance();
        parent::__construct($id);
    }

    public function getEquipments()
    {
        return $this->indexEquipmentService->getEquipments();
    }

    public function detail($id)
    {
        $this->indexEquipmentService->detail($this, $id);
    }

    public function edit($id)
    {
        $this->indexEquipmentService->edit($this, $id);
    }

    public function deleteEquipment($id)
    {
        $this->indexEquipmentService->deleteEquipment($this, $id);
    }

    public function conditionalDelete($id)
    {
        return $this->indexEquipmentService->conditionalDelete($this, $id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.equipment.index-equipment',
            [
                "data" => $this->getData()

            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexEquipmentService->getData($this);
    }
}
