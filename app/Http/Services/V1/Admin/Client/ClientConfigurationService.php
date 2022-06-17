<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Livewire\V1\Admin\Client\AddClient;
use App\Http\Services\Singleton;
use App\Models\V1\EquipmentClient;
use App\Models\V1\ClientType;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\Municipality;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Client;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class ClientConfigurationService extends Singleton
{
    public function mount(Component $component, $client)
    {
        $clientConfiguration = $client->clientConfiguration;
        $component->client = $client;
        if ($clientConfiguration) {
            $component->fill([
                "ssid" => $clientConfiguration->ssid,
                "wifi_password" => $clientConfiguration->wifi_password,
                "measure_type" => $clientConfiguration->measure_type,
                "mqtt_host" => $clientConfiguration->mqtt_host,
                "mqtt_port" => $clientConfiguration->mqtt_port,
                "mqtt_user" => $clientConfiguration->mqtt_user,
                "mqtt_password" => $clientConfiguration->mqtt_password,
                "max_vol_ph_1" => $clientConfiguration->max_vol_ph_1,
                "min_vol_ph_1" => $clientConfiguration->min_vol_ph_1,
                "max_vol_ph_2" => $clientConfiguration->max_vol_ph_2,
                "min_vol_ph_2" => $clientConfiguration->min_vol_ph_2,
                "max_vol_ph_3" => $clientConfiguration->max_vol_ph_3,
                "min_vol_ph_3" => $clientConfiguration->min_vol_ph_3,
                "max_current_ph_1" => $clientConfiguration->max_current_ph_1,
                "min_current_ph_1" => $clientConfiguration->min_current_ph_1,
                "max_current_ph_2" => $clientConfiguration->max_current_ph_2,
                "min_current_ph_2" => $clientConfiguration->min_current_ph_2,
                "max_current_ph_3" => $clientConfiguration->max_current_ph_3,
                "min_current_ph_3" => $clientConfiguration->min_current_ph_3,
                "max_power_ph_1" => $clientConfiguration->max_power_ph_1,
                "min_power_ph_1" => $clientConfiguration->min_power_ph_1,
                "max_power_ph_2" => $clientConfiguration->max_power_ph_2,
                "min_power_ph_2" => $clientConfiguration->min_power_ph_2,
                "max_power_ph_3" => $clientConfiguration->max_power_ph_3,
                "min_power_ph_3" => $clientConfiguration->min_power_ph_3,
                "max_va_ph_1" => $clientConfiguration->max_va_ph_1,
                "min_va_ph_1" => $clientConfiguration->min_va_ph_1,
                "max_va_ph_2" => $clientConfiguration->max_va_ph_2,
                "min_va_ph_2" => $clientConfiguration->min_va_ph_2,
                "max_va_ph_3" => $clientConfiguration->max_va_ph_3,
                "min_va_ph_3" => $clientConfiguration->min_va_ph_3,
                "max_var_ph_1" => $clientConfiguration->max_var_ph_1,
                "min_var_ph_1" => $clientConfiguration->min_var_ph_1,
                "max_var_ph_2" => $clientConfiguration->max_var_ph_2,
                "min_var_ph_2" => $clientConfiguration->min_var_ph_2,
                "max_var_ph_3" => $clientConfiguration->max_var_ph_3,
                "min_var_ph_3" => $clientConfiguration->min_var_ph_3,
                "max_pfp_ph_1" => $clientConfiguration->max_pfp_ph_1,
                "min_pfp_ph_1" => $clientConfiguration->min_pfp_ph_1,
                "max_pfp_ph_2" => $clientConfiguration->max_pfp_ph_2,
                "min_pfp_ph_2" => $clientConfiguration->min_pfp_ph_2,
                "max_pfp_ph_3" => $clientConfiguration->max_pfp_ph_3,
                "min_pfp_ph_3" => $clientConfiguration->min_pfp_ph_3,
                "max_freq" => $clientConfiguration->max_freq,
                "min_freq" => $clientConfiguration->min_freq,
                "max_volt_1_2" => $clientConfiguration->max_volt_1_2,
                "min_volt_1_2" => $clientConfiguration->min_volt_1_2,
                "max_volt_3_1" => $clientConfiguration->max_volt_3_1,
                "min_volt_3_1" => $clientConfiguration->min_volt_3_1,
                "max_volt_2_3" => $clientConfiguration->max_volt_2_3,
                "min_volt_2_3" => $clientConfiguration->min_volt_2_3,
                "max_vthd_ph_1" => $clientConfiguration->max_vthd_ph_1,
                "min_vthd_ph_1" => $clientConfiguration->min_vthd_ph_1,
                "max_vthd_ph_2" => $clientConfiguration->max_vthd_ph_2,
                "min_vthd_ph_2" => $clientConfiguration->min_vthd_ph_2,
                "max_vthd_ph_3" => $clientConfiguration->max_vthd_ph_3,
                "min_vthd_ph_3" => $clientConfiguration->min_vthd_ph_3,
                "max_cthd_ph_1" => $clientConfiguration->max_cthd_ph_1,
                "min_cthd_ph_1" => $clientConfiguration->min_cthd_ph_1,
                "max_cthd_ph_2" => $clientConfiguration->max_cthd_ph_2,
                "min_cthd_ph_2" => $clientConfiguration->min_cthd_ph_2,
                "max_cthd_ph_3" => $clientConfiguration->max_cthd_ph_3,
                "min_cthd_ph_3" => $clientConfiguration->min_cthd_ph_3,
                "max_vthd_ph_1_2" => $clientConfiguration->max_vthd_ph_1_2,
                "min_vthd_ph_1_2" => $clientConfiguration->min_vthd_ph_1_2,
                "max_vthd_ph_2_3" => $clientConfiguration->max_vthd_ph_2_3,
                "min_vthd_ph_2_3" => $clientConfiguration->min_vthd_ph_2_3,
                "max_vthd_ph_3_1" => $clientConfiguration->max_vthd_ph_3_1,
                "min_vthd_ph_3_1" => $clientConfiguration->min_vthd_ph_3_1,
                "real_time_latency" => $clientConfiguration->real_time_latency,
                "storage_latency" => $clientConfiguration->storage_latency,

            ]);
        } else {
            $component->fill([
                "ssid" => "Sin asignar",
                "wifi_password" => "Sin asignar",
                "measure_type" => "Sin asignar",
                "mqtt_host" => "Sin asignar",
                "mqtt_port" => "Sin asignar",
                "mqtt_user" => "Sin asignar",
                "mqtt_password" => "Sin asignar",
                "max_vol_ph_1" => 0.0,
                "min_vol_ph_1" => 0.0,
                "max_vol_ph_2" => 0.0,
                "min_vol_ph_2" => 0.0,
                "max_vol_ph_3" => 0.0,
                "min_vol_ph_3" => 0.0,
                "max_current_ph_1" => 0.0,
                "min_current_ph_1" => 0.0,
                "max_current_ph_2" => 0.0,
                "min_current_ph_2" => 0.0,
                "max_current_ph_3" => 0.0,
                "min_current_ph_3" => 0.0,
                "max_power_ph_1" => 0.0,
                "min_power_ph_1" => 0.0,
                "max_power_ph_2" => 0.0,
                "min_power_ph_2" => 0.0,
                "max_power_ph_3" => 0.0,
                "min_power_ph_3" => 0.0,
                "max_va_ph_1" => 0.0,
                "min_va_ph_1" => 0.0,
                "max_va_ph_2" => 0.0,
                "min_va_ph_2" => 0.0,
                "max_va_ph_3" => 0.0,
                "min_va_ph_3" => 0.0,
                "max_var_ph_1" => 0.0,
                "min_var_ph_1" => 0.0,
                "max_var_ph_2" => 0.0,
                "min_var_ph_2" => 0.0,
                "max_var_ph_3" => 0.0,
                "min_var_ph_3" => 0.0,
                "max_pfp_ph_1" => 0.0,
                "min_pfp_ph_1" => 0.0,
                "max_pfp_ph_2" => 0.0,
                "min_pfp_ph_2" => 0.0,
                "max_pfp_ph_3" => 0.0,
                "min_pfp_ph_3" => 0.0,
                "max_freq" => 0.0,
                "min_freq" => 0.0,
                "max_volt_1_2" => 0.0,
                "min_volt_1_2" => 0.0,
                "max_volt_3_1" => 0.0,
                "min_volt_3_1" => 0.0,
                "max_volt_2_3" => 0.0,
                "min_volt_2_3" => 0.0,
                "max_vthd_ph_1" => 0.0,
                "min_vthd_ph_1" => 0.0,
                "max_vthd_ph_2" => 0.0,
                "min_vthd_ph_2" => 0.0,
                "max_vthd_ph_3" => 0.0,
                "min_vthd_ph_3" => 0.0,
                "max_cthd_ph_1" => 0.0,
                "min_cthd_ph_1" => 0.0,
                "max_cthd_ph_2" => 0.0,
                "min_cthd_ph_2" => 0.0,
                "max_cthd_ph_3" => 0.0,
                "min_cthd_ph_3" => 0.0,
                "max_vthd_ph_1_2" => 0.0,
                "min_vthd_ph_1_2" => 0.0,
                "max_vthd_ph_2_3" => 0.0,
                "min_vthd_ph_2_3" => 0.0,
                "max_vthd_ph_3_1" => 0.0,
                "min_vthd_ph_3_1" => 0.0,
                "real_time_latency" => 0.0,
                "storage_latency" => 0.0,

            ]);
        }
    }

    public function delete(Component $component, $clientId)
    {
        Client::find($clientId)->delete();
        $component->emitTo('livewire-toast', 'show', "Equipo {$clientId} eliminado exitosamente");
        $component->reset();
    }

    public function getClients()
    {
        return Client::get()->paginate(15);
    }

    public function edit(Component $component, $clientId)
    {
        $component->redirectRoute("v1.admin.client.edit.client", ["client" => $clientId]);
    }

    public function details(Component $component, $clientId)
    {
        $component->redirectRoute("v1.admin.client.detail.client", ["client" => $clientId]);
    }

    public function settings(Component $component, $clientId)
    {
        $component->redirectRoute("v1.admin.client.settings", ["client" => $clientId]);
    }

    public function submitForm(Component $component)
    {
        if ($component->client->clientConfiguration) {
            $component->client->clientConfiguration->update($this->getConfigurations($component));
            return;
        }
        $component->client->clientConfiguration()->create($this->getConfigurations($component));
    }

    private function getConfigurations(Component $component)
    {
        return [
            "ssid" => $component->ssid,
            "wifi_password" => $component->wifi_password,
            "measure_type" => $component->measure_type,
            "mqtt_host" => $component->mqtt_host,
            "mqtt_port" => $component->mqtt_port,
            "mqtt_user" => $component->mqtt_user,
            "mqtt_password" => $component->mqtt_password,
            "max_vol_ph_1" => $component->max_vol_ph_1,
            "min_vol_ph_1" => $component->min_vol_ph_1,
            "max_vol_ph_2" => $component->max_vol_ph_2,
            "min_vol_ph_2" => $component->min_vol_ph_2,
            "max_vol_ph_3" => $component->max_vol_ph_3,
            "min_vol_ph_3" => $component->min_vol_ph_3,
            "max_current_ph_1" => $component->max_current_ph_1,
            "min_current_ph_1" => $component->min_current_ph_1,
            "max_current_ph_2" => $component->max_current_ph_2,
            "min_current_ph_2" => $component->min_current_ph_2,
            "max_current_ph_3" => $component->max_current_ph_3,
            "min_current_ph_3" => $component->min_current_ph_3,
            "max_power_ph_1" => $component->max_power_ph_1,
            "min_power_ph_1" => $component->min_power_ph_1,
            "max_power_ph_2" => $component->max_power_ph_2,
            "min_power_ph_2" => $component->min_power_ph_2,
            "max_power_ph_3" => $component->max_power_ph_3,
            "min_power_ph_3" => $component->min_power_ph_3,
            "max_va_ph_1" => $component->max_va_ph_1,
            "min_va_ph_1" => $component->min_va_ph_1,
            "max_va_ph_2" => $component->max_va_ph_2,
            "min_va_ph_2" => $component->min_va_ph_2,
            "max_va_ph_3" => $component->max_va_ph_3,
            "min_va_ph_3" => $component->min_va_ph_3,
            "max_var_ph_1" => $component->max_var_ph_1,
            "min_var_ph_1" => $component->min_var_ph_1,
            "max_var_ph_2" => $component->max_var_ph_2,
            "min_var_ph_2" => $component->min_var_ph_2,
            "max_var_ph_3" => $component->max_var_ph_3,
            "min_var_ph_3" => $component->min_var_ph_3,
            "max_pfp_ph_1" => $component->max_pfp_ph_1,
            "min_pfp_ph_1" => $component->min_pfp_ph_1,
            "max_pfp_ph_2" => $component->max_pfp_ph_2,
            "min_pfp_ph_2" => $component->min_pfp_ph_2,
            "max_pfp_ph_3" => $component->max_pfp_ph_3,
            "min_pfp_ph_3" => $component->min_pfp_ph_3,
            "max_freq" => $component->max_freq,
            "min_freq" => $component->min_freq,
            "max_volt_1_2" => $component->max_volt_1_2,
            "min_volt_1_2" => $component->min_volt_1_2,
            "max_volt_3_1" => $component->max_volt_3_1,
            "min_volt_3_1" => $component->min_volt_3_1,
            "max_volt_2_3" => $component->max_volt_2_3,
            "min_volt_2_3" => $component->min_volt_2_3,
            "max_vthd_ph_1" => $component->max_vthd_ph_1,
            "min_vthd_ph_1" => $component->min_vthd_ph_1,
            "max_vthd_ph_2" => $component->max_vthd_ph_2,
            "min_vthd_ph_2" => $component->min_vthd_ph_2,
            "max_vthd_ph_3" => $component->max_vthd_ph_3,
            "min_vthd_ph_3" => $component->min_vthd_ph_3,
            "max_cthd_ph_1" => $component->max_cthd_ph_1,
            "min_cthd_ph_1" => $component->min_cthd_ph_1,
            "max_cthd_ph_2" => $component->max_cthd_ph_2,
            "min_cthd_ph_2" => $component->min_cthd_ph_2,
            "max_cthd_ph_3" => $component->max_cthd_ph_3,
            "min_cthd_ph_3" => $component->min_cthd_ph_3,
            "max_vthd_ph_1_2" => $component->max_vthd_ph_1_2,
            "min_vthd_ph_1_2" => $component->min_vthd_ph_1_2,
            "max_vthd_ph_2_3" => $component->max_vthd_ph_2_3,
            "min_vthd_ph_2_3" => $component->min_vthd_ph_2_3,
            "max_vthd_ph_3_1" => $component->max_vthd_ph_3_1,
            "min_vthd_ph_3_1" => $component->min_vthd_ph_3_1,
            "real_time_latency" => $component->real_time_latency,
            "storage_latency" => $component->storage_latency,

        ];
    }
}
