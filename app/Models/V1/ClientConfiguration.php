<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;

class ClientConfiguration extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        "ssid",
        "wifi_password",
        "measure_type",
        "mqtt_host",
        "mqtt_port",
        "mqtt_user",
        "mqtt_password",
        "real_time_flag",
        "max_adc_1",
        "min_adc_1",
        "max_adc_2",
        "min_adc_2",
        "max_vol_ph_1",
        "min_vol_ph_1",
        "max_vol_ph_2",
        "min_vol_ph_2",
        "max_vol_ph_3",
        "min_vol_ph_3",
        "max_current_ph_1",
        "min_current_ph_1",
        "max_current_ph_2",
        "min_current_ph_2",
        "max_current_ph_3",
        "min_current_ph_3",
        "max_power_ph_1",
        "min_power_ph_1",
        "max_power_ph_2",
        "min_power_ph_2",
        "max_power_ph_3",
        "min_power_ph_3",
        "max_va_ph_1",
        "min_va_ph_1",
        "max_va_ph_2",
        "min_va_ph_2",
        "max_va_ph_3",
        "min_va_ph_3",
        "max_var_ph_1",
        "min_var_ph_1",
        "max_var_ph_2",
        "min_var_ph_2",
        "max_var_ph_3",
        "min_var_ph_3",
        "max_pfp_ph_1",
        "min_pfp_ph_1",
        "max_pfp_ph_2",
        "min_pfp_ph_2",
        "max_pfp_ph_3",
        "min_pfp_ph_3",
        "max_freq",
        "min_freq",
        "flag_wh_import",
        "flag_wh_export",
        "flag_wh_import_varh",
        "flag_wh_export_varh",
        "max_volt_1_2",
        "min_volt_1_2",
        "max_volt_3_1",
        "min_volt_3_1",
        "max_volt_2_3",
        "min_volt_2_3",
        "max_vthd_ph_1",
        "min_vthd_ph_1",
        "max_vthd_ph_2",
        "min_vthd_ph_2",
        "max_vthd_ph_3",
        "min_vthd_ph_3",
        "max_cthd_ph_1",
        "min_cthd_ph_1",
        "max_cthd_ph_2",
        "min_cthd_ph_2",
        "max_cthd_ph_3",
        "min_cthd_ph_3",
        "max_vthd_ph_1_2",
        "min_vthd_ph_1_2",
        "max_vthd_ph_2_3",
        "min_vthd_ph_2_3",
        "max_vthd_ph_3_1",
        "min_vthd_ph_3_1",
        "real_time_latency",
        "storage_latency",
        "reading_latency",
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function setRemoteConfiguration()
    {
        $client = Client::find($this->client_id);
        $equipment = $client->equipments()->whereEquipmentTypeId(1)->first();
        $message = $this->frameConfig($client->networkOperator->identification, $equipment->serial);
        $topic = "mc/config/".$equipment->serial;
        MQTT::publish($topic, $message);
        MQTT::disconnect();
    }

    private function frameConfig($network_operator, $equipment){
        $alert_config_frame = config('data-frame.alert_config_frame');
        $binary_data = [];
        foreach ($alert_config_frame as $item){
            if ($item['variable_name'] == 'network_operator_id'){
                array_push($binary_data, pack($item['type'], $network_operator));
            } elseif ($item['variable_name'] == 'equipment_id') {
                array_push($binary_data, pack($item['type'], $equipment));
            } elseif ($item['variable_name'] == 'network_operator_new_id') {
                array_push($binary_data, pack($item['type'], $network_operator));
            } elseif ($item['variable_name'] == 'equipment_new_id') {
                array_push($binary_data, pack($item['type'], $equipment));
            } else {
                array_push($binary_data, pack($item['type'], $this->{$item['variable_name']}));
            }
        }
        $message = base64_encode(implode($binary_data));

        return $message;
    }
}
