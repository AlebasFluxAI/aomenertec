<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Http\Services\V1\Admin\Client\ClientConfigurationService;
use App\Models\V1\Client;
use Livewire\Component;

class ConfigurationClient extends Component
{
    public $client;
    public $inputs;
    public $inputs_control;
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
    public $report_rate;
    public $report_rates;
    public $variables;
    public $variables_selected;
    public $select_report;
    public $client_report_variables;
    public $invoicing_day;
    public $requesDetails;

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

    public function submitFormInvoicing()
    {
        $this->configurationClientService->submitFormInvoicing($this);
    }

    public function submitReportRate()
    {
        $this->configurationClientService->submitReportRate($this);
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

    public function submitFormAlert()
    {
        $this->configurationClientService->submitFormAlert($this);
    }public function submitFormControl()
    {
        $this->configurationClientService->submitFormControl($this);
    }

    public function blinkChannel($channel)
    {
        $this->configurationClientService->blinkChannel($this, $channel);
    }

    public function render()
    {
        return view('livewire.v1.admin.client.configuration-client')->extends('layouts.v1.app');
    }

    protected function rules()
    {
        return $this->configurationClientService->rules($this);
    }
}
