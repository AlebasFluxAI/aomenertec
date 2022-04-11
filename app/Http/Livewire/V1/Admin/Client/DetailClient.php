<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\DetailClientService;
use App\Models\V1\Client;
use Livewire\Component;

class DetailClient extends Component
{
    public $client;
    public $equipment;
    public $serials;
    public $client_types;
    public $client_type_id;
    public $client_type;
    public $equipment_types;
    private $detailClientService;

    protected $rules = [
        'equipment.*.id' => 'required|min:2',
        'equipment.*.index' => 'required|min:2',
        'equipment.*.type_id' => 'required',
        'equipment.*.type' => 'required|min:2',
        'equipment.*.serial' => 'required|min:2',
        'equipment.*.picked' => 'required',
        'equipment.*.post' => 'required|min:2',
        'equipment.*.disable' => 'required|min:2',
    ];
    public function __construct()
    {
        parent::__construct();
        $this->detailClientService = DetailClientService::getInstance();
    }

    public function mount(Client $client)
    {
        $this->detailClientService->mount($this, $client);
    }

    public function addInputEquipment()
    {
        $this->detailClientService->addInputEquipment($this);
    }

    public function deleteInputEquipment()
    {
        $this->detailClientService->deleteInputEquipment($this);
    }

    public function updated($property_name, $value)
    {
        $this->detailClientService->updated($this, $property_name, $value);
    }

    public function assignEquipment($equipment_id, $index)
    {
        $this->detailClientService->assignEquipment($this, $equipment_id, $index);
    }

    public function assignEquipmentFirst($index)
    {
        $this->detailClientService->assignEquipmentFirst($this, $index);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.detail-client')
            ->extends('layouts.v1.app');
    }
}
