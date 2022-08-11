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


    public function deleteEquipment($id)
    {
        $this->indexEquipmentService->deleteEquipment($this, $id);
    }

    public function conditionalDeleteEquipment($id)
    {
        return $this->indexEquipmentService->conditionalDeleteEquipment($this, $id);
    }

    public function removeEquipmentAdmin($id)
    {
        $this->indexEquipmentService->removeEquipmentAdmin($this, $id);
    }
    public function conditionalRemoveEquipmentAdmin($id)
    {
        return $this->indexEquipmentService->conditionalRemoveEquipmentAdmin($this, $id);
    }

    public function removeEquipmentNetworkOperator($id)
    {
        $this->indexEquipmentService->removeEquipmentNetworkOperator($this, $id);
    }
    public function conditionalRemoveEquipmentNetworkOperator($id)
    {
        return $this->indexEquipmentService->conditionalRemoveEquipmentNetworkOperator($this, $id);
    }

    public function removeEquipmentTechnician($id)
    {
        $this->indexEquipmentService->removeEquipmentTechnician($this, $id);
    }
    public function conditionalRemoveEquipmentTechnician($id)
    {
        return $this->indexEquipmentService->conditionalRemoveEquipmentTechnician($this, $id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.equipment.index-equipment',
            [
                "data" => $this->getData(),
                "permissionRemove" => $this->getPermission(),
                "functionRemoveEquipment" => $this->getFunctionRemoveEquipment(),
                "conditionalRemoveEquipment" => $this->getConditionalRemoveEquipment(),
                "availableFlag" => $this->getAvailableFlag(),

            ]
        )->extends('layouts.v1.app');
    }

    public function getAvailableFlag()
    {
        return $this->indexEquipmentService->getAvailableFlag($this);
    }
    public function getPermission()
    {
        return $this->indexEquipmentService->getPermission($this);
    }
    public function getFunctionRemoveEquipment()
    {
        return $this->indexEquipmentService->getFunctionRemoveEquipment($this);
    }
    public function getConditionalRemoveEquipment()
    {
        return $this->indexEquipmentService->getConditionalRemoveEquipment($this);
    }
    public function getData()
    {
        return $this->indexEquipmentService->getData($this);
    }
}
