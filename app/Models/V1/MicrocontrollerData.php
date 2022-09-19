<?php

namespace App\Models\V1;

use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\AlertHistory;
use App\Models\V1\Client;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpMqtt\Client\Facades\MQTT;
use App\Models\V1\Equipment;

use Illuminate\Support\Facades\Config;
use PhpOption\None;

class MicrocontrollerData extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        "id",
        "raw_json",
        "client_id",
        "accumulated_real_consumption",
        "interval_real_consumption",
        "interval_reactive_consumption",
        "accumulated_reactive_consumption",
        "source_timestamp",
        "accumulated_reactive_inductive_consumption",
        "accumulated_reactive_capacitive_consumption",
        "interval_reactive_capacitive_consumption",
        "interval_reactive_inductive_consumption",
        "type",
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function alertHistories()
    {
        return $this->hasMany(AlertHistory::class);
    }

    public function dailyMicrocontrollerData()
    {
        return $this->hasOne(DailyMicrocontrollerData::class);

    }

    public function jsonEdit()
    {
        $date = new Carbon();
        if (is_string($this->raw_json)){
            $json = json_decode($this->raw_json, true);
        } elseif (is_array($this->raw_json)){
            $json = $this->raw_json;
        }

        $timestamp_unix = $json['timestamp'];
        $current_time = $date->setTimestamp($timestamp_unix);
        $equipment_serial = $json['equipment_id'];
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
            ->first();
        if ($equipment == null) {
            $this->delete();
            return;
        }
        $client = $equipment->clients()->first();
        if ($client == null) {
            $this->delete();
            return;
        }

        if ($client->microcontrollerData()->where('source_timestamp', $current_time->format('Y-m-d H:i:s'))->exists()) {
            $this->delete();

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

        $this->client_id = $client->id;
        $this->accumulated_real_consumption = $json['import_wh'];
        $this->interval_real_consumption = $json['kwh_interval'];
        $this->accumulated_reactive_consumption = $json['import_VArh'];
        $this->interval_reactive_consumption = $json['varh_interval'];
        $this->accumulated_reactive_capacitive_consumption = $json['varCh_acumm'];
        $this->accumulated_reactive_inductive_consumption = $json['varLh_acumm'];
        $this->interval_reactive_capacitive_consumption = $json['varCh_interval'];
        $this->interval_reactive_inductive_consumption = $json['varLh_interval'];
        $this->raw_json = $json;
        $this->saveQuietly();
        $this->alertEnergyEvent();
        UpdatedMicrocontrollerDataJob::dispatch($this);
    }

    public function alertEnergyEvent()
    {
        $binary_flags = sprintf("%064b", ($this->raw_json['flags']));
        $this->source_timestamp = new Carbon($this->source_timestamp);
        $is_wifi = substr($binary_flags, 2, 1);

        $client = Client::find($this->client_id);

        $value = 0;
        $unix_time = $this->raw_json["timestamp"];
        $current_time = new Carbon();
        $current_time_aux = new Carbon();
        $current_time->setTimestamp($unix_time);
        $current_time_aux->setTimestamp($unix_time);
        $current_time->subHour();
        $current_time_aux->subMonth();
        $energy_alerts = $client->clientAlertConfiguration()->where('flag_id', '>=', 47)
            ->where('max_alert', '>', 0)->get();
        $energy_control = $client->clientAlertConfiguration()->where('flag_id', '>=', 47)
            ->where('active_control', true)->where('max_control', '>', 0)->get();
        $alerts = $energy_alerts->merge($energy_control);
        $energy_hour = $client->microcontrollerData()->whereBetween('source_timestamp', [$current_time->format('Y-m-d H:00:00'),$current_time->format('Y-m-d H:59:59')])
            ->orderBy('source_timestamp', 'desc')->first();
        $energy_month = $client->microcontrollerData()->whereBetween('source_timestamp', [$current_time_aux->format('Y-m-1 00:00:00'),$current_time_aux->format('Y-m-t 23:59:59')])
            ->orderBy('source_timestamp', 'desc')->first();

        if (!$energy_hour) {
            $energy_hour = $client->microcontrollerData()
                ->whereBetween('source_timestamp', [$this->source_timestamp->format('Y-m-d H:00:00'),$this->source_timestamp->format('Y-m-d H:59:59')])
                ->orderBy('source_timestamp')
                ->first();
        }
        if (!$energy_month) {
            $energy_month = $client->microcontrollerData()
                ->whereBetween('source_timestamp', [$this->source_timestamp->format('Y-m-1 00:00:00'),$this->source_timestamp->format('Y-m-t 23:59:59')])
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
        if ($flag_id == 47) {
            $value = $this->accumulated_real_consumption - $energy_month->accumulated_real_consumption;
        } elseif ($flag_id == 48) {
            $value = $this->accumulated_reactive_inductive_consumption - $energy_month->accumulated_reactive_inductive_consumption;
        } elseif ($flag_id == 49) {
            $value = $this->accumulated_reactive_capacitive_consumption - $energy_month->accumulated_reactive_capacitive_consumption;
        } elseif ($flag_id == 50) {
            $value = $this->accumulated_real_consumption - $energy_hour->accumulated_real_consumption;
        } elseif ($flag_id == 51) {
            $value = $this->accumulated_reactive_inductive_consumption - $energy_hour->accumulated_reactive_inductive_consumption;
        } elseif ($flag_id == 52) {
            $value = $this->accumulated_reactive_capacitive_consumption - $energy_hour->accumulated_reactive_capacitive_consumption;
        } else {
            if ($this->interval_real_consumption != 0) {
                $value = ($this->interval_reactive_inductive_consumption * 100) / $this->interval_real_consumption;
            } else {
                $value = 0;
            }
        }
        return $value;
    }

    private function createAlert($value, $type, $alert)
    {
        if ($alert->flag_id == 53){
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) {
                $query->whereBetween("source_timestamp", [$this->source_timestamp->copy()->subMinutes(10)->format('Y-m-d H:i:s'), $this->source_timestamp->format('Y-m-d H:i:s')]);
            })->exists()) {
                ClientAlert::create([
                    'client_id' => $this->client_id,
                    'microcontroller_data_id' => $this->id,
                    'client_alert_configuration_id' => $alert->id,
                    'value' => $value,
                    'type' => $type
                ]);
            }
        }
        elseif ($alert->flag_id == 47
            || $alert->flag_id == 48
            || $alert->flag_id == 49) {
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) {
                $query->whereBetween("source_timestamp", [$this->source_timestamp->format('Y-m-1 00:00:00'), $this->source_timestamp->format('Y-m-t 23:59:59')]);
            })->exists()) {
                ClientAlert::create([
                    'client_id' => $this->client_id,
                    'microcontroller_data_id' => $this->id,
                    'client_alert_configuration_id' => $alert->id,
                    'value' => $value,
                    'type' => $type
                ]);
            }
        } else {
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) {
                $query->whereBetween("source_timestamp", [$this->source_timestamp->format('Y-m-d H:00:00'), $this->source_timestamp->format('Y-m-d H:59:59')]);
            })->where('type', $type)->exists()) {
                ClientAlert::create([
                    'client_id' => $this->client_id,
                    'microcontroller_data_id' => $this->id,
                    'client_alert_configuration_id' => $alert->id,
                    'value' => $value,
                    'type' => $type
                ]);
            }
        }
    }
}
