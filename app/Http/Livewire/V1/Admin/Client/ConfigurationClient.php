<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientConfigurationService;
use App\Http\Services\V1\Admin\Client\IndexClientService;
use App\Http\Services\V1\Admin\Equipment\EquipmentIndexService;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use Livewire\Component;
use Livewire\WithPagination;

class ConfigurationClient extends Component
{
    public $client;
    public $inputs;
    public $checks;
    public $client_config;
    public $client_config_alert;
    public $digital_outputs;
    public $placeholders;
    public $storage_latency_options;
    public $storage_latency_types;
    public $frame_types;
    public $notification_types;
    public $channels;
    public $outputs_selected;
    public $model;
    public $control_options;
    public $active_real_time;


    private $configurationClientService;


    public function __construct($id = null)
    {
        $this->configurationClientService = ClientConfigurationService::getInstance();
        parent::__construct($id);
    }

    public function mount(Client $client)
    {
        $this->configurationClientService->mount($this, $client);
    }

    protected function rules()
    {
        return $this->configurationClientService->rules($this);
    }

    public function outputRelation($id)
    {
        $this->configurationClientService->outputRelation($this, $id);
    }

    public function assignmentOutput($id, $index)
    {
        $this->configurationClientService->assignmentOutput($this, $id, $index);
    }

    public function updated($key, $value)
    {
        $this->configurationClientService->updated($this, $key, $value);
    }

    public function updatedClientConfig($value, $key)
    {
        $this->configurationClientService->updatedClientConfig($this, $value, $key);
    }


    public function submitFormConection()
    {
        $this->configurationClientService->submitFormConection($this);
    }
    public function submitFormPermission(){
        $this->configurationClientService->submitFormPermission($this);

    }

    public function submitFormAlert()
    {
        $this->configurationClientService->submitFormAlert($this);
    }

    public function blinkChannel($channel)
    {
        $this->configurationClientService->blinkChannel($this, $channel);
    }


    public function render()
    {
        return view('livewire.v1.admin.client.configuration-client')->extends('layouts.v1.app');
    }
}
