<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\DetailClientService;
use App\Models\V1\Client;
use Livewire\Component;

class DetailClient extends Component
{
    public $client;
    public $equipment;
    private $detailClientService;


    public function __construct()
    {
        parent::__construct();
        $this->detailClientService = DetailClientService::getInstance();
    }

    public function mount(Client $client)
    {

        $this->detailClientService->mount($this, $client);
    }

    public function conditionalDeleteTechnician($id)
    {
        return $this->detailClientService->conditionalDeleteTechnician($this, $id);
    }

    public function deleteTechnician($id)
    {
        $this->detailClientService->deleteTechnician($this, $id);
    }

    public function disableTechnician($id)
    {
        $this->detailClientService->disableTechnician($this, $id);
    }

    public function getEnabledTechnician($id)
    {
        return $this->detailClientService->getEnabledTechnician($this, $id);
    }

    public function getEnabledAuxTechnician($id)
    {
        return $this->detailClientService->getEnabledAuxTechnician($this, $id);
    }

    public function conditionalLinkEquipmentTechnician($id)
    {
        return $this->detailClientService->conditionalLinkEquipmentTechnician($this, $id);
    }

    public function conditionalLinkClientsTechnician($id)
    {
        return $this->detailClientService->conditionalLinkClientsTechnician($this, $id);
    }

    public function conditionalDeleteSupervisor($id)
    {
        return $this->detailClientService->conditionalDeleteSupervisor($this, $id);
    }

    public function deleteSupervisor($id)
    {
        $this->detailClientService->deleteSupervisor($this, $id);
    }

    public function disableSupervisor($id)
    {
        $this->detailClientService->disableSupervisor($this, $id);
    }

    public function conditionalLinkClientsSupervisor($id)
    {
        return $this->detailClientService->conditionalLinkClientsSupervisor($this, $id);
    }

    public function getEnabledSupervisor($id)
    {
        return $this->detailClientService->getEnabledSupervisor($this, $id);
    }

    public function getEnabledAuxSupervisor($id)
    {
        return $this->detailClientService->getEnabledAuxSupervisor($this, $id);
    }

    public function conditionalDeleteEquipment($id)
    {
        return $this->detailClientService->conditionalDeleteEquipment($this, $id);
    }

    public function deleteEquipment($id)
    {
        $this->detailClientService->deleteEquipment($this, $id);
    }

    public function conditionalRemoveEquipmentAdmin($id)
    {
        return $this->detailClientService->conditionalRemoveEquipmentAdmin($this, $id);
    }

    public function removeEquipmentAdmin($id)
    {
        $this->detailClientService->removeEquipmentAdmin($this, $id);
    }


    public function deprecateEquipment($id)
    {
        return $this->detailClientService->deprecateEquipment($id);
    }

    public function conditionalEquipmentDeprecate($id)
    {

        return $this->detailClientService->conditionalEquipmentDeprecate($id);
    }


    public function conditionalEquipmentRepaired($id)
    {
        return $this->detailClientService->conditionalEquipmentRepaired($id);
    }

    public function repairEquipment($id)
    {
        return $this->detailClientService->repairEquipment($id);
    }

    public function disableClient($clientId)
    {
        $this->detailClientService->disableClient($this, $clientId);

    }

    public function render()
    {
        return view('livewire.v1.admin.client.detail-client')
            ->extends('layouts.v1.app');
    }
}
