<?php

namespace App\Models\V1;

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

    public function updateData(){
        $decode = bin2hex(base64_decode($this->raw_json));
        $timestamp = (unpack('l', hex2bin(substr($decode, 64, 8)))[1]);
        $date = new Carbon();
        $date->setTimestamp($timestamp);
        $this->source_timestamp = $date->format("Y-m-d H:i:s");
        $this->saveQuietly();
    }

    public function miningData()
    {
        $data_frame = config('data-frame.data_frame');
        $decode = bin2hex(base64_decode($this->raw_json));
        foreach ($data_frame as $data) {
            try {
                $split = substr($decode, ($data['start']), ($data['lenght']));
                $bin = hex2bin($split);
                if ($data['start'] >= 440) {
                    $json[$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                    $json["data".$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                } else {
                    if ($data['variable_name'] == "flags") {
                        $json[$data['variable_name']] = strval(unpack($data['type'], $bin)[1]);
                    } else {
                        $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                    }
                }

                if (is_nan($json[$data['variable_name']])) {
                    $json[$data['variable_name']] = null;
                }

                if ($data['variable_name'] == "ph3_varLh_acumm") {
                    break;
                }
            } catch (Exception $e) {
                echo 'Excepción capturada: ', $e->getMessage(), "\n";
            }
        }
        $this->raw_json = $json;
        $this->saveQuietly();
        if ($json['import_wh'] == 0) {
            $this->delete();
            return;
        }
        $this->jsonEdit($json);
    }

    private function jsonEdit($json)
    {
        $date = new Carbon();
        $timestamp_unix = $json['timestamp'];   /////// timesatmp correct
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
            $date = new Carbon();
            $date->setTimestamp($json['timestamp']);
            $this->source_timestamp = $date->format("Y-m-d H:i:s");
            $this->saveQuietly();
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
                ->whereBetween('source_timestamp', [$reference_hour->format('Y-m-d H:00:00'),$reference_hour->format('Y-m-d H:59:59')])
                ->orderBy('source_timestamp', 'desc')
                ->first();

            if (!$reference_data) {
                $reference_data = $client->microcontrollerData()
                    ->whereBetween('source_timestamp', [$current_time->format('Y-m-d H:00:00'),$current_time->format('Y-m-d H:59:59')])
                    ->orderBy('source_timestamp')
                    ->first();
            }

            if (empty($reference_data)) {
                if($last_data != null){
                    $json['kwh_interval'] = $json['import_wh'] - $last_raw_json['import_wh'];
                    $json['varh_interval'] = $json['import_VArh'] - $last_raw_json['import_VArh'];
                    $json['varCh_acumm'] = $json['ph1_varCh_acumm'] + $json['ph2_varCh_acumm'] +$json['ph3_varCh_acumm'];
                    $json['varLh_acumm'] = $json['ph1_varLh_acumm'] + $json['ph2_varLh_acumm'] +$json['ph3_varLh_acumm'];
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
                $json['varCh_acumm'] = $json['ph1_varCh_acumm'] + $json['ph2_varCh_acumm'] +$json['ph3_varCh_acumm'];
                $json['varLh_acumm'] = $json['ph1_varLh_acumm'] + $json['ph2_varLh_acumm'] +$json['ph3_varLh_acumm'];
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
        $this->source_timestamp = $current_time->format('Y-m-d H:i:s');
        $this->accumulated_real_consumption = $json['import_wh'];
        $this->interval_real_consumption = $json['kwh_interval'];
        $this->accumulated_reactive_consumption = $json['import_VArh'];
        $this->interval_reactive_consumption = $json['varh_interval'];
        $this->accumulated_reactive_capacitive_consumption = $json['varCh_acumm'];
        $this->accumulated_reactive_inductive_consumption = $json['varLh_acumm'];
        $this->interval_reactive_capacitive_consumption = $json['varCh_interval'];
        $this->interval_reactive_inductive_consumption = $json['varLh_interval'];
        $this->raw_json = $json;
        $this->update();
    }

    public function intervalMiningData()
    {
        $unix_time = $this->raw_json["timestamp"];
        $current_time = new DateTime();
        $current_time->setTimestamp($unix_time);
        $year = $current_time->format('Y');
        $month = $current_time->format('m');
        $day = $current_time->format('d');
        $hour = $current_time->format('H');
        $minute = $current_time->format('i');
        $last_day_month = $current_time->format('t');

        HourlyMicrocontrollerData::create([
            'year' => $year,
            'month' => $month,
            'day' => $day,
            'hour' => $hour,
            'minute' => $minute,
            'client_id' => $this->client_id,
            'microcontroller_data_id' => $this->id,
        ]);
        if ($minute == 59) {
            if ($this->interval_real_consumption == 0) {
                $penalizable_inductive = $this->interval_reactive_inductive_consumption;
            } else {
                $percent_penalizable_inductive = ($this->interval_reactive_inductive_consumption * 100) / $this->interval_real_consumption;
                if ($percent_penalizable_inductive >= 50) {
                    $penalizable_inductive = ($this->interval_real_consumption * $percent_penalizable_inductive / 100) - ($this->interval_real_consumption * 0.5);
                } else {
                    $penalizable_inductive = 0;
                }
            }
            DailyMicrocontrollerData::create([
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'hour' => $hour,
                'client_id' => $this->client_id,
                'microcontroller_data_id' => $this->id,
                'interval_real_consumption' => $this->interval_real_consumption,
                'interval_reactive_capacitive_consumption' => $this->interval_reactive_capacitive_consumption,
                'interval_reactive_inductive_consumption' => $this->interval_reactive_inductive_consumption,
                'penalizable_reactive_capacitive_consumption' => $this->interval_reactive_capacitive_consumption,
                'penalizable_reactive_inductive_consumption' => $penalizable_inductive,
            ]);

            if ($hour == 23) {
                $json = $this->raw_json;
                $data_frame = collect(config('data-frame.data_frame'));
                $accum_variable = $data_frame->where('bolean_accum', true);
                $penalizable_inductive_day = 0;
                $penalizable_capacitive_day = 0;
                $interval_active_day = 0;
                $interval_capacitive_day = 0;
                $interval_inductive_day = 0;
                foreach ($accum_variable as $variable) {
                    $json[$variable['variable_name']] = 0;
                }
                $data_day = Client::find($this->client_id)->dailyMicrocontrollerData()->where('year', $year)->where('month', $month)->where('day', $day)->get();
                if (count($data_day) > 0) {
                    foreach ($data_day as $item) {
                        foreach ($accum_variable as $index=>$variable) {
                            $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                            $json[$variable['variable_name']] = $json[$variable['variable_name']] + $raw_json[$variable['variable_name']];
                        }
                        $interval_active_day = $interval_active_day + $item->interval_real_consumption;
                        $interval_capacitive_day = $interval_capacitive_day + $item->interval_reactive_capacitive_consumption;
                        $interval_inductive_day = $interval_inductive_day + $item->interval_reactive_inductive_consumption;
                        $penalizable_inductive_day = $penalizable_inductive_day + $item->penalizable_reactive_inductive_consumption;
                        $penalizable_capacitive_day = $penalizable_capacitive_day + $item->penalizable_reactive_capacitive_consumption;
                    }
                }
                MonthlyMicrocontrollerData::create([
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                    'client_id' => $this->client_id,
                    'microcontroller_data_id' => $this->id,
                    'interval_real_consumption' => $interval_active_day,
                    'interval_reactive_capacitive_consumption' => $interval_capacitive_day,
                    'interval_reactive_inductive_consumption' => $interval_inductive_day,
                    'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_day,
                    'penalizable_reactive_inductive_consumption' => $penalizable_inductive_day,
                    'raw_json' => json_encode($json),
                ]);
                if ($day == $last_day_month) {
                    $json = $this->raw_json;
                    $penalizable_inductive_month = 0;
                    $penalizable_capacitive_month = 0;
                    $interval_active_month = 0;
                    $interval_capacitive_month = 0;
                    $interval_inductive_month = 0;
                    foreach ($accum_variable as $variable) {
                        $json[$variable['variable_name']] = 0;
                    }
                    $data_month = Client::find($this->client_id)->monthlyMicrocontrollerData()->where('year', $year)->where('month', $month)->get();
                    if (count($data_month) > 0) {
                        foreach ($data_month as $item) {
                            foreach ($accum_variable as $variable) {
                                $raw_json = json_decode($item->raw_json, true);
                                $json[$variable['variable_name']] = $json[$variable['variable_name']] + $raw_json[$variable['variable_name']];
                            }
                            $interval_active_month = $interval_active_month + $item->interval_real_consumption;
                            $interval_capacitive_month = $interval_capacitive_month + $item->interval_reactive_capacitive_consumption;
                            $interval_inductive_month = $interval_inductive_month + $item->interval_reactive_inductive_consumption;
                            $penalizable_inductive_month = $penalizable_inductive_month + $item->penalizable_reactive_inductive_consumption;
                            $penalizable_capacitive_month = $penalizable_capacitive_month + $item->penalizable_reactive_capacitive_consumption;
                        }
                    }
                    AnnualMicrocontrollerData::create([
                        'year' => $year,
                        'month' => $month,
                        'client_id' => $this->client_id,
                        'microcontroller_data_id' => $this->id,
                        'interval_real_consumption' => $interval_active_month,
                        'interval_reactive_capacitive_consumption' => $interval_capacitive_month,
                        'interval_reactive_inductive_consumption' => $interval_inductive_month,
                        'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_month,
                        'penalizable_reactive_inductive_consumption' => $penalizable_inductive_month,
                        'raw_json' => json_encode($json),
                    ]);
                }
            }
        }
    }

    public function alertEvent()
    {
        $flags_frame = config('data-frame.flags_frame');
        $binary_flags = sprintf("%064b", ($this->raw_json['flags']));
        $is_alert = substr($binary_flags, 1, 1);
        $this->source_timestamp = new Carbon($this->source_timestamp);
        $is_wifi = substr($binary_flags, 2, 1);

        $client = Client::find($this->client_id);
        if ($is_alert == "1") {
            foreach ($flags_frame as $item) {
                if ($item['id'] >= 14 and $item['id'] <= 46) {
                    $type = "";
                    $split = substr($binary_flags, $item['bit'], 1);
                    if ($split == "1") {
                        if ($item['flag_name'] == 'flagOpened') {
                            $value = 1;
                            $type = ClientAlert::ALERT;
                        } else {
                            $value = $this->raw_json[$item['variable_name']];
                            $alert = $client->clientAlertConfiguration()->where('flag_id', $item['id'])->first();
                            if ($alert->active_control) {
                                if ($alert->min_alert != 0) {
                                    if ($value < $alert->min_alert) {
                                        $type = ClientAlert::ALERT;
                                    }
                                }
                                if ($alert->max_alert != 0) {
                                    if ($value > $alert->max_alert) {
                                        $type = ClientAlert::ALERT;
                                    }
                                }
                                if ($alert->min_control != 0) {
                                    if ($value < $alert->min_control) {
                                        $type = ClientAlert::CONTROL;
                                    }
                                }
                                if ($alert->max_control != 0) {
                                    if ($value > $alert->max_control) {
                                        $type = ClientAlert::CONTROL;
                                    }
                                }
                            } else {
                                if ($alert->min_alert != 0) {
                                    if ($value < $alert->min_alert) {
                                        $type = ClientAlert::ALERT;
                                    }
                                }
                                if ($alert->max_alert != 0) {
                                    if ($value > $alert->max_alert) {
                                        $type = ClientAlert::ALERT;
                                    }
                                }
                            }
                        }
                        if ($type != "") {
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
        } else {
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
            if ($this->interval_real_consumption != 0){
                $value = ($this->interval_reactive_inductive_consumption * 100) / $this->interval_real_consumption;
            } else{
                $value = 0;
            }
        }
        return $value;
    }

    private function createAlert($value, $type, $alert)
    {
        if ($alert->flag_id == 47
            ||$alert->flag_id == 48
            ||$alert->flag_id == 49) {
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) {
                $query->whereBetween("source_timestamp", [$this->source_timestamp->format('Y-m-1 00:00:00'), $this->source_timestamp->format('Y-m-t 23:59:59') ]);
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
                $query->whereBetween("source_timestamp", [$this->source_timestamp->format('Y-m-d H:00:00'), $this->source_timestamp->format('Y-m-d H:59:59') ]);
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
