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
    public $client_id;
    public $ssid;
    public $wifi_password;
    public $measure_type;
    public $mqtt_host;
    public $mqtt_port;
    public $mqtt_user;
    public $mqtt_password;
    public $real_time_flag;
    public $max_adc_1;
    public $min_adc_1;
    public $max_adc_2;
    public $min_adc_2;
    public $max_vol_ph_1;
    public $min_vol_ph_1;
    public $max_vol_ph_2;
    public $min_vol_ph_2;
    public $max_vol_ph_3;
    public $min_vol_ph_3;
    public $max_current_ph_1;
    public $min_current_ph_1;
    public $max_current_ph_2;
    public $min_current_ph_2;
    public $max_current_ph_3;
    public $min_current_ph_3;
    public $max_power_ph_1;
    public $min_power_ph_1;
    public $max_power_ph_2;
    public $min_power_ph_2;
    public $max_power_ph_3;
    public $min_power_ph_3;
    public $max_va_ph_1;
    public $min_va_ph_1;
    public $max_va_ph_2;
    public $min_va_ph_2;
    public $max_va_ph_3;
    public $min_va_ph_3;
    public $max_var_ph_1;
    public $min_var_ph_1;
    public $max_var_ph_2;
    public $min_var_ph_2;
    public $max_var_ph_3;
    public $min_var_ph_3;
    public $max_pfp_ph_1;
    public $min_pfp_ph_1;
    public $max_pfp_ph_2;
    public $min_pfp_ph_2;
    public $max_pfp_ph_3;
    public $min_pfp_ph_3;
    public $max_freq;
    public $min_freq;
    public $flag_wh_import;
    public $flag_wh_export;
    public $flag_wh_import_varh;
    public $flag_wh_export_varh;
    public $max_volt_1_2;
    public $min_volt_1_2;
    public $max_volt_3_1;
    public $min_volt_3_1;
    public $max_volt_2_3;
    public $min_volt_2_3;
    public $max_vthd_ph_1;
    public $min_vthd_ph_1;
    public $max_vthd_ph_2;
    public $min_vthd_ph_2;
    public $max_vthd_ph_3;
    public $min_vthd_ph_3;
    public $max_cthd_ph_1;
    public $min_cthd_ph_1;
    public $max_cthd_ph_2;
    public $min_cthd_ph_2;
    public $max_cthd_ph_3;
    public $min_cthd_ph_3;
    public $max_vthd_ph_1_2;
    public $min_vthd_ph_1_2;
    public $max_vthd_ph_2_3;
    public $min_vthd_ph_2_3;
    public $max_vthd_ph_3_1;
    public $min_vthd_ph_3_1;
    public $real_time_latency;
    public $storage_latency;
    public $reading_latency;

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

    public function getClient()
    {
        return $this->configurationClientService->getClient();
    }

    public function details($id)
    {
        $this->configurationClientService->details($this, $id);
    }

    public function edit($id)
    {
        $this->configurationClientService->edit($this, $id);
    }

    public function delete($id)
    {
        $this->configurationClientService->delete($this, $id);
    }

    public function settings($id)
    {
        $this->configurationClientService->settings($this, $id);
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
