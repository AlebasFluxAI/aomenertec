<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\EquipmentType;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SerializeMicrocontrollerDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $model;
    public function __construct(MicrocontrollerData $model)
    {
        $this->model = $model->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->jsonEdit();
    }

    public function jsonEdit()
    {
        $date = new Carbon();
        if (is_string($this->model->raw_json)) {
            $json = json_decode($this->model->raw_json, true);
        } elseif (is_array($this->model->raw_json)) {
            $json = $this->model->raw_json;
        }

        $timestamp_unix = $json['timestamp'];
        $current_time = $date->setTimestamp($timestamp_unix);
        $equipment_serial = str_pad($json['equipment_id'], 6, "0", STR_PAD_LEFT);
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
            ->first();
        if ($equipment == null) {
            $this->model->delete();
            return;
        }
        $client = $equipment->clients()->first();
        if ($client == null) {
            $this->model->delete();
            return;
        }

        if ($client->microcontrollerData()->where('source_timestamp', $current_time->format('Y-m-d H:i:s'))->exists()) {

            $this->model->delete();
            return;
        }

        if (!$client->microcontrollerData()->exists()) {
            $json['kwh_interval'] = 0;
            $json['varh_interval'] = 0;
            $json['varCh_acumm'] = $json['ph1_varCh_acumm'] + $json['ph2_varCh_acumm'] + $json['ph3_varCh_acumm'];
            $json['varLh_acumm'] = $json['ph1_varLh_acumm'] + $json['ph2_varLh_acumm'] + $json['ph3_varLh_acumm'];
            $json['ph1_varCh_interval'] = 0;
            $json['ph1_varLh_interval'] = 0;
            $json['ph2_varCh_interval'] = 0;
            $json['ph2_varLh_interval'] = 0;
            $json['ph3_varCh_interval'] = 0;
            $json['ph3_varLh_interval'] = 0;
            $json['ph1_kwh_interval'] = 0;
            $json['ph2_kwh_interval'] = 0;
            $json['ph3_kwh_interval'] = 0;
            $json['ph1_varh_interval'] = 0;
            $json['ph2_varh_interval'] = 0;
            $json['ph3_varh_interval'] = 0;
            $json['varCh_interval'] = 0;
            $json['varLh_interval'] = 0;
        } else {
            $last_data = $client->microcontrollerData()->orderBy('source_timestamp', 'desc')->first();
            $last_raw_json = json_decode($last_data->raw_json, true);

            $reference_hour = new Carbon();
            $reference_hour->setTimestamp($timestamp_unix);
            $reference_hour->subHour();

            $reference_data = $client->microcontrollerData()
                ->whereBetween('source_timestamp', [$reference_hour->format('Y-m-d H:00:00'), $reference_hour->format('Y-m-d H:59:59')])
                ->orderBy('source_timestamp', 'desc')
                ->first();

            if (!$reference_data) {
                $reference_data = $client->microcontrollerData()
                    ->whereBetween('source_timestamp', [$current_time->format('Y-m-d H:00:00'), $current_time->format('Y-m-d H:59:59')])
                    ->orderBy('source_timestamp')
                    ->first();
            }

            if (empty($reference_data)) {
                if ($last_data != null) {
                    $json['kwh_interval'] = $json['import_wh'] - $last_raw_json['import_wh'];
                    $json['varh_interval'] = $json['import_VArh'] - $last_raw_json['import_VArh'];
                    $json['varCh_acumm'] = $json['ph1_varCh_acumm'] + $json['ph2_varCh_acumm'] + $json['ph3_varCh_acumm'];
                    $json['varLh_acumm'] = $json['ph1_varLh_acumm'] + $json['ph2_varLh_acumm'] + $json['ph3_varLh_acumm'];
                    $json['ph1_varCh_acumm'] = $json['ph1_varCh_acumm'] + $last_raw_json['ph1_varCh_acumm'];
                    $json['ph1_varLh_acumm'] = $json['ph1_varLh_acumm'] + $last_raw_json['ph1_varLh_acumm'];
                    $json['ph2_varCh_acumm'] = $json['ph2_varCh_acumm'] + $last_raw_json['ph2_varCh_acumm'];
                    $json['ph2_varLh_acumm'] = $json['ph2_varLh_acumm'] + $last_raw_json['ph2_varLh_acumm'];
                    $json['ph3_varCh_acumm'] = $json['ph3_varCh_acumm'] + $last_raw_json['ph3_varCh_acumm'];
                    $json['ph3_varLh_acumm'] = $json['ph3_varLh_acumm'] + $last_raw_json['ph3_varLh_acumm'];
                    $json['varCh_acumm'] = $json['varCh_acumm'] + $last_raw_json['varCh_acumm'];
                    $json['varLh_acumm'] = $json['varLh_acumm'] + $last_raw_json['varLh_acumm'];
                    $json['ph1_varCh_interval'] = $json['ph1_varCh_acumm'] - $last_raw_json['ph1_varCh_acumm'];
                    $json['ph1_varLh_interval'] = $json['ph1_varLh_acumm'] - $last_raw_json['ph1_varLh_acumm'];
                    $json['ph2_varCh_interval'] = $json['ph2_varCh_acumm'] - $last_raw_json['ph2_varCh_acumm'];
                    $json['ph2_varLh_interval'] = $json['ph2_varLh_acumm'] - $last_raw_json['ph2_varLh_acumm'];
                    $json['ph3_varCh_interval'] = $json['ph3_varCh_acumm'] - $last_raw_json['ph3_varCh_acumm'];
                    $json['ph3_varLh_interval'] = $json['ph3_varLh_acumm'] - $last_raw_json['ph3_varLh_acumm'];
                    $json['ph1_kwh_interval'] = $json['ph1_import_kwh'] - $last_raw_json['ph1_import_kwh'];
                    $json['ph2_kwh_interval'] = $json['ph2_import_kwh'] - $last_raw_json['ph2_import_kwh'];
                    $json['ph3_kwh_interval'] = $json['ph3_import_kwh'] - $last_raw_json['ph3_import_kwh'];
                    $json['ph1_varh_interval'] = $json['ph1_import_kvarh'] - $last_raw_json['ph1_import_kvarh'];
                    $json['ph2_varh_interval'] = $json['ph2_import_kvarh'] - $last_raw_json['ph2_import_kvarh'];
                    $json['ph3_varh_interval'] = $json['ph3_import_kvarh'] - $last_raw_json['ph3_import_kvarh'];
                    $json['varCh_interval'] = $json['varCh_acumm'] - $last_raw_json['varCh_acumm'];
                    $json['varLh_interval'] = $json['varLh_acumm'] - $last_raw_json['varLh_acumm'];
                }
            } else {
                $reference_data_json = json_decode($reference_data->raw_json, true);
                $json['kwh_interval'] = $json['import_wh'] - $reference_data_json['import_wh'];
                $json['varh_interval'] = $json['import_VArh'] - $reference_data_json['import_VArh'];
                $json['varCh_acumm'] = $json['ph1_varCh_acumm'] + $json['ph2_varCh_acumm'] + $json['ph3_varCh_acumm'];
                $json['varLh_acumm'] = $json['ph1_varLh_acumm'] + $json['ph2_varLh_acumm'] + $json['ph3_varLh_acumm'];
                $json['ph1_varCh_acumm'] = $json['ph1_varCh_acumm'] + $last_raw_json['ph1_varCh_acumm'];
                $json['ph1_varLh_acumm'] = $json['ph1_varLh_acumm'] + $last_raw_json['ph1_varLh_acumm'];
                $json['ph2_varCh_acumm'] = $json['ph2_varCh_acumm'] + $last_raw_json['ph2_varCh_acumm'];
                $json['ph2_varLh_acumm'] = $json['ph2_varLh_acumm'] + $last_raw_json['ph2_varLh_acumm'];
                $json['ph3_varCh_acumm'] = $json['ph3_varCh_acumm'] + $last_raw_json['ph3_varCh_acumm'];
                $json['ph3_varLh_acumm'] = $json['ph3_varLh_acumm'] + $last_raw_json['ph3_varLh_acumm'];
                $json['varCh_acumm'] = $json['varCh_acumm'] + $last_raw_json['varCh_acumm'];
                $json['varLh_acumm'] = $json['varLh_acumm'] + $last_raw_json['varLh_acumm'];
                $json['ph1_varCh_interval'] = $json['ph1_varCh_acumm'] - $reference_data_json['ph1_varCh_acumm'];
                $json['ph1_varLh_interval'] = $json['ph1_varLh_acumm'] - $reference_data_json['ph1_varLh_acumm'];
                $json['ph2_varCh_interval'] = $json['ph2_varCh_acumm'] - $reference_data_json['ph2_varCh_acumm'];
                $json['ph2_varLh_interval'] = $json['ph2_varLh_acumm'] - $reference_data_json['ph2_varLh_acumm'];
                $json['ph3_varCh_interval'] = $json['ph3_varCh_acumm'] - $reference_data_json['ph3_varCh_acumm'];
                $json['ph3_varLh_interval'] = $json['ph3_varLh_acumm'] - $reference_data_json['ph3_varLh_acumm'];
                $json['ph1_kwh_interval'] = $json['ph1_import_kwh'] - $reference_data_json['ph1_import_kwh'];
                $json['ph2_kwh_interval'] = $json['ph2_import_kwh'] - $reference_data_json['ph2_import_kwh'];
                $json['ph3_kwh_interval'] = $json['ph3_import_kwh'] - $reference_data_json['ph3_import_kwh'];
                $json['ph1_varh_interval'] = $json['ph1_import_kvarh'] - $reference_data_json['ph1_import_kvarh'];
                $json['ph2_varh_interval'] = $json['ph2_import_kvarh'] - $reference_data_json['ph2_import_kvarh'];
                $json['ph3_varh_interval'] = $json['ph3_import_kvarh'] - $reference_data_json['ph3_import_kvarh'];
                $json['varCh_interval'] = $json['varCh_acumm'] - $reference_data_json['varCh_acumm'];
                $json['varLh_interval'] = $json['varLh_acumm'] - $reference_data_json['varLh_acumm'];
            }
        }

        $this->model->client_id = $client->id;
        $this->model->accumulated_real_consumption = $json['import_wh'];
        $this->model->interval_real_consumption = $json['kwh_interval'];
        $this->model->accumulated_reactive_consumption = $json['import_VArh'];
        $this->model->interval_reactive_consumption = $json['varh_interval'];
        $this->model->accumulated_reactive_capacitive_consumption = $json['varCh_acumm'];
        $this->model->accumulated_reactive_inductive_consumption = $json['varLh_acumm'];
        $this->model->interval_reactive_capacitive_consumption = $json['varCh_interval'];
        $this->model->interval_reactive_inductive_consumption = $json['varLh_interval'];
        $this->model->raw_json = $json;
        $this->model->saveQuietly();
        $this->alertEnergyEvent();
        $this->updateHourlyData();
    }

    public function alertEnergyEvent()
    {
        $binary_flags = sprintf("%064b", ($this->model->raw_json['flags']));
        $this->model->source_timestamp = new Carbon($this->model->source_timestamp);
        $is_wifi = substr($binary_flags, 2, 1);
        $client = Client::find($this->model->client_id);
        if($is_wifi == 1){
            $is_wifi = true;
        } else {
            $is_wifi = false;
        }
        if (!$client->clientConfiguration()->exists()) {
            ClientConfiguration::create([
                "client_id" => $client->id,
                "ssid" => "",
                "wifi_password" => "",
                "mqtt_host" => "3.12.98.178",
                "mqtt_port" => "1883",
                "mqtt_user" => "enertec",
                "mqtt_password" => "enertec2020**",
                "real_time_latency" => 30,
                "active_real_time" => false,
                "storage_latency" => 1,
                "storage_type_latency" => ClientConfiguration::STORAGE_LATENCY_TYPE_HOURLY,
                "frame_type" => ClientConfiguration::FRAME_TYPE_ACTIVE_REACTIVE_ENERGY_VARIABLES,
                "digital_outputs" => 0,

            ]);
        }
        $real_time_flag = $client->clientConfiguration()->first();
        $real_time_flag->real_time_flag = $is_wifi;
        $real_time_flag->save();
        $value = 0;
        $unix_time = $this->model->raw_json["timestamp"];
        $current_time = new Carbon();
        $current_time_aux = new Carbon();
        $current_time->setTimestamp($unix_time);
        $current_time_aux->setTimestamp($unix_time);
        $current_time->subHour();
        $current_time_aux->subMonth();
        $energy_alerts = $client->clientAlertConfiguration()->where('flag_id', '>=', 50)
            ->where('max_alert', '>', 0)->get();
        $energy_control = $client->clientAlertConfiguration()->where('flag_id', '>=', 50)
            ->where('active_control', true)->where('max_control', '>', 0)->get();
        $alerts = $energy_alerts->merge($energy_control);
        $energy_hour = $client->microcontrollerData()->whereBetween('source_timestamp', [$current_time->format('Y-m-d H:00:00'),$current_time->format('Y-m-d H:59:59')])
            ->orderBy('source_timestamp', 'desc')->first();
        $energy_month = $client->microcontrollerData()->whereBetween('source_timestamp', [$current_time_aux->format('Y-m-1 00:00:00'),$current_time_aux->format('Y-m-t 23:59:59')])
            ->orderBy('source_timestamp', 'desc')->first();

        if (!$energy_hour) {
            $energy_hour = $client->microcontrollerData()
                ->whereBetween('source_timestamp', [$this->model->source_timestamp->format('Y-m-d H:00:00'),$this->model->source_timestamp->format('Y-m-d H:59:59')])
                ->orderBy('source_timestamp')
                ->first();
        }
        if (!$energy_month) {
            $energy_month = $client->microcontrollerData()
                ->whereBetween('source_timestamp', [$this->model->source_timestamp->format('Y-m-1 00:00:00'),$this->model->source_timestamp->format('Y-m-t 23:59:59')])
                ->orderBy('source_timestamp')
                ->first();
        }
        foreach ($alerts as $alert) {
            $value = $this->calculateValueAlert($alert->flag_id, $energy_month, $energy_hour);
            if ($alert->active_control) {
                if ($alert->max_alert >= $value  and $alert->max_control >= $value) {
                    continue;
                } else {
                    if ($alert->max_alert < $value) {
                        $type = ClientAlert::ALERT;
                    }
                    if ($alert->max_control < $value) {
                        $type = ClientAlert::CONTROL;
                    }
                    $this->createAlert($value, $type, $alert);
                }
            } else {
                if ($alert->max_alert <= $value) {
                    $type = ClientAlert::ALERT;
                    $this->createAlert($value, $type, $alert);
                }
            }
        }
    }

    private function calculateValueAlert($flag_id, $energy_month, $energy_hour)
    {
        if ($flag_id == 50) {
            $value = $this->model->accumulated_real_consumption - $energy_month->accumulated_real_consumption;
        } elseif ($flag_id == 51) {
            $value = $this->model->accumulated_reactive_inductive_consumption - $energy_month->accumulated_reactive_inductive_consumption;
        } elseif ($flag_id == 52) {
            $value = $this->model->accumulated_reactive_capacitive_consumption - $energy_month->accumulated_reactive_capacitive_consumption;
        } elseif ($flag_id == 53) {
            $value = $this->model->accumulated_real_consumption - $energy_hour->accumulated_real_consumption;
        } elseif ($flag_id == 54) {
            $value = $this->model->accumulated_reactive_inductive_consumption - $energy_hour->accumulated_reactive_inductive_consumption;
        } elseif ($flag_id == 55) {
            $value = $this->model->accumulated_reactive_capacitive_consumption - $energy_hour->accumulated_reactive_capacitive_consumption;
        } else {
            if ($this->model->interval_real_consumption != 0) {
                $value = ($this->model->interval_reactive_inductive_consumption * 100) / $this->model->interval_real_consumption;
            } else {
                $value = 0;
            }
        }
    }

    private function createAlert($value, $type, $alert)
    {
        if ($alert->flag_id == 56) {
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) {
                $query->whereBetween("source_timestamp", [$this->model->source_timestamp->copy()->subMinutes(10)->format('Y-m-d H:i:s'), $this->model->source_timestamp->format('Y-m-d H:i:s')]);
            })->exists()) {
                ClientAlert::create([
                    'client_id' => $this->model->client_id,
                    'microcontroller_data_id' => $this->model->id,
                    'client_alert_configuration_id' => $alert->id,
                    'value' => $value,
                    'type' => $type
                ]);
            }
        } elseif ($alert->flag_id == 50
            || $alert->flag_id == 51
            || $alert->flag_id == 52) {
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) {
                $query->whereBetween("source_timestamp", [$this->model->source_timestamp->format('Y-m-1 00:00:00'), $this->model->source_timestamp->format('Y-m-t 23:59:59')]);
            })->exists()) {
                ClientAlert::create([
                    'client_id' => $this->model->client_id,
                    'microcontroller_data_id' => $this->model->id,
                    'client_alert_configuration_id' => $alert->id,
                    'value' => $value,
                    'type' => $type
                ]);
            }
        } else {
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) {
                $query->whereBetween("source_timestamp", [$this->model->source_timestamp->format('Y-m-d H:00:00'), $this->model->source_timestamp->format('Y-m-d H:59:59')]);
            })->where('type', $type)->exists()) {
                ClientAlert::create([
                    'client_id' => $this->model->client_id,
                    'microcontroller_data_id' => $this->model->id,
                    'client_alert_configuration_id' => $alert->id,
                    'value' => $value,
                    'type' => $type
                ]);
            }
        }
    }

    public function updateHourlyData()
    {
        $current_time = new Carbon($this->model->source_timestamp);
        $year = $current_time->format('Y');
        $month = $current_time->format('m');
        $day = $current_time->format('d');
        $hour = $current_time->format('H');
        if ($this->model->interval_real_consumption == 0) {
            $penalizable_inductive = $this->model->interval_reactive_inductive_consumption;
        } else {
            $percent_penalizable_inductive = ($this->model->interval_reactive_inductive_consumption * 100) / $this->model->interval_real_consumption;
            if ($percent_penalizable_inductive >= 50) {
                $penalizable_inductive = ($this->model->interval_real_consumption * $percent_penalizable_inductive / 100) - ($this->model->interval_real_consumption * 0.5);
            } else {
                $penalizable_inductive = 0;
            }
        }
        HourlyMicrocontrollerData::updateOrCreate(
            ['year' => $year,
                'month' => $month,
                'day' => $day,
                'hour' => $hour,
                'client_id' => $this->model->client_id],
            ['microcontroller_data_id' => $this->model->id,
                'interval_real_consumption' => $this->model->interval_real_consumption,
                'interval_reactive_capacitive_consumption' => $this->model->interval_reactive_capacitive_consumption,
                'interval_reactive_inductive_consumption' => $this->model->interval_reactive_inductive_consumption,
                'penalizable_reactive_capacitive_consumption' => $this->model->interval_reactive_capacitive_consumption,
                'penalizable_reactive_inductive_consumption' => $penalizable_inductive]
        );
    }
}
