<?php

namespace App\Models\V1;

use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Jobs\V1\OrderData\AverageDailyConsumptionJob;
use App\Jobs\V1\OrderData\AverageHourlyConsumptionJob;
use App\Models\Traits\ImageableTrait;
use App\Models\Traits\PaginatorTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MicrocontrollerData extends Model
{
    use HasFactory;
    use SoftDeletes;
    use PaginatorTrait;
    use ImageableTrait;


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
        "is_alert",
        "manually",
        "status"
    ];

    public const PENDING_TIMESTAMP = "pending_timestamp";
    public const PROCESING_TIMESTAMP = "procesing_timestamp";
    public const SUCCESS_TIMESTAMP = "success_timestamp";
    public const PENDING_UNPACK = "pending_unpack";
    public const SUCCESS_UNPACK = "success_unpack";
    public const PENDING_REORDER = "pending_reorder";


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, "imageable")->whereType("evidences");
    }

    public function evidences()
    {
        return $this->morphMany(Image::class, "imageable")->whereType("evidences");
    }

    public function alertHistories()
    {
        return $this->hasMany(AlertHistory::class);
    }

    public function monthlyMicrocontrollerData()
    {
        return $this->hasOne(MonthlyMicrocontrollerData::class);
    }

    public function jsonEdit($flag)
    {
        $date = new Carbon();
        if (is_string($this->raw_json)) {
            $json = json_decode($this->raw_json, true);
            if ($json == null) {
                return;
            }
        } elseif (is_array($this->raw_json)) {
            $json = $this->raw_json;
        }

        $timestamp_unix = $json['timestamp'];
        $current_time = $date->copy()->setTimestamp($timestamp_unix);
        $this->source_timestamp = $current_time->format('Y-m-d H:i:s');
        $equipment_serial = str_pad($json['equipment_id'], 6, "0", STR_PAD_LEFT);
        $equipment_type = EquipmentType::whereType('GABINETE')->first();
        $equipment = $equipment_type->equipment()->whereSerial($equipment_serial)->first();

        if($equipment == null){
            $equipment_type = EquipmentType::whereType('MEDIDOR ELECTRICO')->first();
            $equipment = $equipment_type->equipment()->whereSerial($equipment_serial)->first();
        }

        if ($equipment == null) {
            if ($this->workOrder()->exists()) {
                $this->workOrder()->forceDelete();
            }
            $this->forceDelete();
            return;
        }
        $client = $equipment->clients()->first();
        if ($client == null) {
            if ($this->workOrder()->exists()) {
                $this->workOrder()->forceDelete();
            }
            $this->forceDelete();
            return;
        }
        if ($flag) {
            if ($client->stopUnpackClient()->exists()) {
                return;
            }
            if (MicrocontrollerData::where('client_id', $client->id)->where('source_timestamp', $current_time->format('Y-m-d H:i:s'))->exists()) {
                if ($this->hourlyMicrocontrollerData()->exists()) {
                    $this->hourlyMicrocontrollerData()->forceDelete();
                }
                if ($this->dailyMicrocontrollerData()->exists()) {
                    $this->dailyMicrocontrollerData()->forceDelete();
                }
                if ($this->clientAlert()->exists()) {
                    $this->clientAlert()->forceDelete();
                }
                if ($this->workOrder()->exists()) {
                    $this->workOrder()->forceDelete();
                }
                $this->forceDelete();
                return;
            }
        } else {
            if (!($client->stopUnpackClient()->exists())) {
                StopUnpackDataClient::create(['client_id' => $client->id]);
            }
        }


        if (!(MicrocontrollerData::whereClientId($client->id)->exists())) {
            $json['kwh_interval'] = 0;
            $json['varh_interval'] = 0;
            $json['varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($json['ph2_varCh_acumm']) + floatval($json['ph3_varCh_acumm']);
            $json['varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($json['ph2_varLh_acumm']) + floatval($json['ph3_varLh_acumm']);
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
            if ($flag) {
                $last_data = $client->microcontrollerData()->orderBy('source_timestamp', 'desc')->first();
                if ($last_data) {
                    if (new Carbon($last_data->source_timestamp) >= $current_time) {
                        $this->status = MicrocontrollerData::PENDING_REORDER;
                        $this->delete();
                        // alamacenar para reubicar dato
                        return;
                    }
                    $last_raw_json = json_decode($last_data->raw_json, true);
                    if ($json['import_wh'] <= 0) {

                        if ($last_raw_json['import_wh'] > 0) {
                            $this->forceDelete();
                            return;
                        }
                    }
                }
            } else {
                $last_data = $client->microcontrollerData()->where('source_timestamp', '<', $current_time->format('Y-m-d H:i:s'))->orderBy('source_timestamp', 'desc')->first();
                if ($last_data) {
                    if (new Carbon($last_data->source_timestamp) >= $current_time) {
                        $this->forceDelete();
                        return;
                    }
                    $last_raw_json = json_decode($last_data->raw_json, true);
                    if ($json['import_wh'] <= 0) {
                        if ($last_raw_json['import_wh'] > 0) {
                            $this->forceDelete();
                            return;
                        }
                    }
                } else {
                    $json['kwh_interval'] = 0;
                    $json['varh_interval'] = 0;
                    $json['varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($json['ph2_varCh_acumm']) + floatval($json['ph3_varCh_acumm']);
                    $json['varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($json['ph2_varLh_acumm']) + floatval($json['ph3_varLh_acumm']);
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
                }
            }
            $reference_hour = $current_time->copy()->subHour();
            $reference_data = $client->microcontrollerData()
                ->whereBetween('source_timestamp', [$reference_hour->format('Y-m-d H:00:00'), $reference_hour->format('Y-m-d H:59:59')])
                ->orderBy('source_timestamp', 'desc')
                ->first();
            if (empty($reference_data)) {
                $reference_data = $client->microcontrollerData()->where('source_timestamp', '<', $reference_hour->format('Y-m-d H:00:00'))->orderBy('source_timestamp', 'desc')->first();
            }
            if (empty($reference_data)) {
                if ($last_data) {
                    $json['kwh_interval'] = $json['import_wh'] - $last_raw_json['import_wh'];
                    $json['varh_interval'] = $json['import_VArh'] - $last_raw_json['import_VArh'];
                    $json['varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($json['ph2_varCh_acumm']) + floatval($json['ph3_varCh_acumm']);
                    $json['varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($json['ph2_varLh_acumm']) + floatval($json['ph3_varLh_acumm']);
                    /*if($json['varLh_acumm'] < $last_raw_json['varLh_acumm']){
                        $json['ph1_varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($last_raw_json['ph1_varCh_acumm']);
                        $json['ph1_varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($last_raw_json['ph1_varLh_acumm']);
                        $json['ph2_varCh_acumm'] = floatval($json['ph2_varCh_acumm']) + floatval($last_raw_json['ph2_varCh_acumm']);
                        $json['ph2_varLh_acumm'] = floatval($json['ph2_varLh_acumm']) + floatval($last_raw_json['ph2_varLh_acumm']);
                        $json['ph3_varCh_acumm'] = floatval($json['ph3_varCh_acumm']) + floatval($last_raw_json['ph3_varCh_acumm']);
                        $json['ph3_varLh_acumm'] = floatval($json['ph3_varLh_acumm']) + floatval($last_raw_json['ph3_varLh_acumm']);
                        $json['varCh_acumm'] = $json['varCh_acumm'] + floatval($last_raw_json['varCh_acumm']);
                        $json['varLh_acumm'] = $json['varLh_acumm'] + floatval($last_raw_json['varLh_acumm']);
                    }*/
                    $json['ph1_varCh_interval'] = $json['ph1_varCh_acumm'] - floatval($last_raw_json['ph1_varCh_acumm']);
                    $json['ph1_varLh_interval'] = $json['ph1_varLh_acumm'] - floatval($last_raw_json['ph1_varLh_acumm']);
                    $json['ph2_varCh_interval'] = $json['ph2_varCh_acumm'] - floatval($last_raw_json['ph2_varCh_acumm']);
                    $json['ph2_varLh_interval'] = $json['ph2_varLh_acumm'] - floatval($last_raw_json['ph2_varLh_acumm']);
                    $json['ph3_varCh_interval'] = $json['ph3_varCh_acumm'] - floatval($last_raw_json['ph3_varCh_acumm']);
                    $json['ph3_varLh_interval'] = $json['ph3_varLh_acumm'] - floatval($last_raw_json['ph3_varLh_acumm']);
                    $json['ph1_kwh_interval'] = $json['ph1_import_kwh'] - $last_raw_json['ph1_import_kwh'];
                    $json['ph2_kwh_interval'] = $json['ph2_import_kwh'] - $last_raw_json['ph2_import_kwh'];
                    $json['ph3_kwh_interval'] = $json['ph3_import_kwh'] - $last_raw_json['ph3_import_kwh'];
                    $json['ph1_varh_interval'] = $json['ph1_import_kvarh'] - $last_raw_json['ph1_import_kvarh'];
                    $json['ph2_varh_interval'] = $json['ph2_import_kvarh'] - $last_raw_json['ph2_import_kvarh'];
                    $json['ph3_varh_interval'] = $json['ph3_import_kvarh'] - $last_raw_json['ph3_import_kvarh'];
                    $json['varCh_interval'] = $json['varCh_acumm'] - floatval($last_raw_json['varCh_acumm']);
                    $json['varLh_interval'] = $json['varLh_acumm'] - floatval($last_raw_json['varLh_acumm']);
                }
            } else {
                if ($last_data) {
                    $reference_data_json = json_decode($reference_data->raw_json, true);
                    $json['kwh_interval'] = $json['import_wh'] - $reference_data_json['import_wh'];
                    $json['varh_interval'] = $json['import_VArh'] - $reference_data_json['import_VArh'];
                    $json['varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($json['ph2_varCh_acumm']) + floatval($json['ph3_varCh_acumm']);
                    $json['varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($json['ph2_varLh_acumm']) + floatval($json['ph3_varLh_acumm']);
                    /*if($json['varLh_acumm'] < $last_raw_json['varLh_acumm']){
                        $json['ph1_varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($last_raw_json['ph1_varCh_acumm']);
                        $json['ph1_varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($last_raw_json['ph1_varLh_acumm']);
                        $json['ph2_varCh_acumm'] = floatval($json['ph2_varCh_acumm']) + floatval($last_raw_json['ph2_varCh_acumm']);
                        $json['ph2_varLh_acumm'] = floatval($json['ph2_varLh_acumm']) + floatval($last_raw_json['ph2_varLh_acumm']);
                        $json['ph3_varCh_acumm'] = floatval($json['ph3_varCh_acumm']) + floatval($last_raw_json['ph3_varCh_acumm']);
                        $json['ph3_varLh_acumm'] = floatval($json['ph3_varLh_acumm']) + floatval($last_raw_json['ph3_varLh_acumm']);
                        $json['varCh_acumm'] = $json['varCh_acumm'] + floatval($last_raw_json['varCh_acumm']);
                        $json['varLh_acumm'] = $json['varLh_acumm'] + floatval($last_raw_json['varLh_acumm']);
                    }*/
                    $json['ph1_varCh_interval'] = $json['ph1_varCh_acumm'] - floatval($reference_data_json['ph1_varCh_acumm']);
                    $json['ph1_varLh_interval'] = $json['ph1_varLh_acumm'] - floatval($reference_data_json['ph1_varLh_acumm']);
                    $json['ph2_varCh_interval'] = $json['ph2_varCh_acumm'] - floatval($reference_data_json['ph2_varCh_acumm']);
                    $json['ph2_varLh_interval'] = $json['ph2_varLh_acumm'] - floatval($reference_data_json['ph2_varLh_acumm']);
                    $json['ph3_varCh_interval'] = $json['ph3_varCh_acumm'] - floatval($reference_data_json['ph3_varCh_acumm']);
                    $json['ph3_varLh_interval'] = $json['ph3_varLh_acumm'] - floatval($reference_data_json['ph3_varLh_acumm']);
                    $json['ph1_kwh_interval'] = $json['ph1_import_kwh'] - $reference_data_json['ph1_import_kwh'];
                    $json['ph2_kwh_interval'] = $json['ph2_import_kwh'] - $reference_data_json['ph2_import_kwh'];
                    $json['ph3_kwh_interval'] = $json['ph3_import_kwh'] - $reference_data_json['ph3_import_kwh'];
                    $json['ph1_varh_interval'] = $json['ph1_import_kvarh'] - $reference_data_json['ph1_import_kvarh'];
                    $json['ph2_varh_interval'] = $json['ph2_import_kvarh'] - $reference_data_json['ph2_import_kvarh'];
                    $json['ph3_varh_interval'] = $json['ph3_import_kvarh'] - $reference_data_json['ph3_import_kvarh'];
                    $json['varCh_interval'] = $json['varCh_acumm'] - floatval($reference_data_json['varCh_acumm']);
                    $json['varLh_interval'] = $json['varLh_acumm'] - floatval($reference_data_json['varLh_acumm']);
                }
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
        if (!$flag) {
            $equals = $client->microcontrollerData()->where('source_timestamp', $current_time->format('Y-m-d H:i:s'))->get();
            if ($equals) {
                foreach ($equals as $item) {
                    if ($item->id != $this->id) {
                        if ($item->hourlyMicrocontrollerData()->exists()) {
                            $item->hourlyMicrocontrollerData()->forceDelete();
                        }
                        if ($item->dailyMicrocontrollerData()->exists()) {
                            $item->dailyMicrocontrollerData()->forceDelete();
                        }
                        if ($item->clientAlert()->exists()) {
                            $item->clientAlert()->forceDelete();
                        }
                        $item->forceDelete();
                        return;
                    }
                }

            }
        }
        $this->saveQuietly();
        if ($flag) {
            if ($date->gt($current_time)) {
                if ($date->diffInMinutes($current_time) >= 35) {
                    dispatch(new AverageHourlyConsumptionJob($this->client->id, $current_time))->onQueue('spot3');
                }
                if ($date->diffInDays($current_time) >= 1) {
                    if ($date->diffInMinutes($current_time) >= 65) {
                        dispatch(new AverageDailyConsumptionJob($this->client->id, $current_time))->onQueue('spot3');
                    }
                }
            }
            dispatch(new UpdatedMicrocontrollerDataJob($this))->onQueue('spot');
            //$this->alertEnergyEvent();
        }
    }

    public function hourlyMicrocontrollerData()
    {
        return $this->hasOne(HourlyMicrocontrollerData::class);
    }

    public function dailyMicrocontrollerData()
    {
        return $this->hasOne(DailyMicrocontrollerData::class);
    }

    public function clientAlert()
    {
        return $this->hasOne(ClientAlert::class);
    }
    public function workOrder()
    {
        return $this->hasOne(WorkOrder::class);
    }

    public function alertEnergyEvent()
    {
        $binary_flags = sprintf("%064b", ($this->raw_json['flags']));
        $this->source_timestamp = new Carbon($this->source_timestamp);
        $client = Client::find($this->client_id);
       /* $is_wifi = substr($binary_flags, 2, 1);
        if ($is_wifi == 1) {
            $is_wifi = true;
        } else {
            $is_wifi = false;
        }*/
        $offset_outputs = [0, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        if ($client->digitalOutputs()->exists()) {
            $client_outputs = $client->digitalOutputs()->get();
            foreach ($client_outputs as $output) {
                $status = substr($binary_flags, $offset_outputs[$output->number], 1);
                if ($status == 1) {
                    $status = true;
                } else {
                    $status = false;
                }
                $output->status = $status;
                $output->save();
            }
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
        //$real_time_flag = $client->clientConfiguration()->first();
        //$real_time_flag->real_time_flag = $is_wifi;
        //$real_time_flag->save();
        $value = 0;
        $unix_time = $this->raw_json["timestamp"];
        $current_time = new Carbon();
        $current_time_aux = new Carbon();
        $current_time->setTimestamp($unix_time);
        $current_time_aux->setTimestamp($unix_time);
        $current_time->subHour();
        $current_time_aux->subMonth();
        $energy_alerts = $client->clientAlertConfiguration()->where('flag_id', '>', 50)
            ->where('max_alert', '>', 0)->get();
        $energy_control = $client->clientAlertConfiguration()->where('flag_id', '>', 50)
            ->where('active_control', true)->where('max_control', '>', 0)->get();
        $alerts = $energy_alerts->merge($energy_control);
        $energy_hour = $client->microcontrollerData()->whereBetween('source_timestamp', [$current_time->format('Y-m-d H:00:00'), $current_time->format('Y-m-d H:59:59')])
            ->orderBy('source_timestamp', 'desc')->first();
        $energy_month = $client->microcontrollerData()->whereBetween('source_timestamp', [$current_time_aux->format('Y-m-1 00:00:00'), $current_time_aux->format('Y-m-t 23:59:59')])
            ->orderBy('source_timestamp', 'desc')->first();

        if (!$energy_hour) {
            $energy_hour = $client->microcontrollerData()
                ->whereBetween('source_timestamp', [$this->source_timestamp->format('Y-m-d H:00:00'), $this->source_timestamp->format('Y-m-d H:59:59')])
                ->orderBy('source_timestamp')
                ->first();
        }
        if (!$energy_month) {
            $energy_month = $client->microcontrollerData()
                ->whereBetween('source_timestamp', [$this->source_timestamp->format('Y-m-1 00:00:00'), $this->source_timestamp->format('Y-m-t 23:59:59')])
                ->orderBy('source_timestamp')
                ->first();
        }
        foreach ($alerts as $alert) {
            $value = $this->calculateValueAlert($alert->flag_id, $energy_month, $energy_hour);
            if ($alert->active_control) {
                if ($alert->max_alert >= $value and $alert->max_control >= $value) {
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

    public function calculateValueAlert($flag_id, $energy_month, $energy_hour)
    {
        $value = 0;
        if ($energy_month) {
            if ($flag_id == 51) {
                $value = $this->accumulated_real_consumption - $energy_month->accumulated_real_consumption;
            } elseif ($flag_id == 52) {
                $value = $this->accumulated_reactive_inductive_consumption - $energy_month->accumulated_reactive_inductive_consumption;
            } elseif ($flag_id == 53) {
                $value = $this->accumulated_reactive_capacitive_consumption - $energy_month->accumulated_reactive_capacitive_consumption;
            }
        }
        if ($energy_hour) {
            if ($flag_id == 54) {
                $value = $this->accumulated_real_consumption - $energy_hour->accumulated_real_consumption;
            } elseif ($flag_id == 55) {
                $value = $this->accumulated_reactive_inductive_consumption - $energy_hour->accumulated_reactive_inductive_consumption;
            } elseif ($flag_id == 56) {
                $value = $this->accumulated_reactive_capacitive_consumption - $energy_hour->accumulated_reactive_capacitive_consumption;
            }
        }

        if ($flag_id == 57) {
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
        if ($alert->flag_id == 57) {
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) {
                $query->whereBetween("source_timestamp", [$this->source_timestamp->copy()->subMinutes(25)->format('Y-m-d H:i:s'), $this->source_timestamp->format('Y-m-d H:i:s')]);
            })->exists()) {
                ClientAlert::create([
                    'client_id' => $this->client_id,
                    'microcontroller_data_id' => $this->id,
                    'client_alert_configuration_id' => $alert->id,
                    'value' => $value,
                    'type' => $type,
                    'source_timestamp' => $this->source_timestamp->format('Y-m-d H:i:s')
                ]);
            }
        } elseif ($alert->flag_id == 51
            || $alert->flag_id == 52
            || $alert->flag_id == 53) {
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) {
                $query->whereBetween("source_timestamp", [$this->source_timestamp->format('Y-m-1 00:00:00'), $this->source_timestamp->format('Y-m-t 23:59:59')]);
            })->exists()) {
                ClientAlert::create([
                    'client_id' => $this->client_id,
                    'microcontroller_data_id' => $this->id,
                    'client_alert_configuration_id' => $alert->id,
                    'value' => $value,
                    'type' => $type,
                    'source_timestamp' => $this->source_timestamp->format('Y-m-d H:i:s')
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
                    'type' => $type,
                    'source_timestamp' => $this->source_timestamp->format('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
