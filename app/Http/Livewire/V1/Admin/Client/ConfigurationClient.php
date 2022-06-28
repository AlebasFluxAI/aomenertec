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
    public $outputs_selected;


    private $configurationClientService;

    protected $rules = [
        'client_config.ssid'=>'required',
        'client_config.wifi_password'=>'required',
        'client_config.mqtt_host'=>'required',
        'client_config.mqtt_port'=>'required',
        'client_config.mqtt_user'=>'required',
        'client_config.mqtt_password'=>'required',
        'client_config.real_time_latency'=>'required',
        'client_config.storage_latency'=>'required',
        'client_config.digital_outputs'=>'required',
        'client_config_alert.*.min_alert'=>'required',
        'client_config_alert.*.max_alert'=>'required',
        'client_config_alert.*.min_control'=>'required',
        'client_config_alert.*.max_control'=>'required',
        'client_config_alert.*.active_control'=>'required',
        'checks.*.output' => 'required'
    ];

    public function __construct($id = null)
    {
        $this->configurationClientService = ClientConfigurationService::getInstance();
        parent::__construct($id);
    }

    public function mount(Client $client)
    {
        $this->configurationClientService->mount($this, $client);
    }
    public function outputRelation($id)
    {
        $this->configurationClientService->outputRelation($this, $id);
    }
    public function assignmentOutput($id)
        {
            $this->configurationClientService->assignmentOutput($this, $id);
        }

    public function updatedClientConfig($value, $key){

        $this->configurationClientService->updatedClientConfig($this, $value, $key);
    }

    public function submitForm()
    {
        $this->configurationClientService->submitForm($this);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.configuration-client')->extends('layouts.v1.app');
    }
}
