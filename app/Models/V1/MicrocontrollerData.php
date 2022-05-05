<?php

namespace App\Models\V1;

use App\Models\V1\AlertHistory;
use App\Models\V1\Client;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
                } else {
                    $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
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
        $this->jsonEdit($json);
        //$this->alert();
    }


    private function jsonEdit($json)
    {
        $decode = bin2hex(base64_decode($this->raw_json));
        $split = substr($decode, 16, 16);
        $bin = hex2bin($split);
        $equipment_serial = unpack('Q', $bin)[1];
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
            ->first();
        $client = $equipment->clients()->first();
        $this->client_id = $client->id;
        /*$split = substr($decode, 64, 8);
        $bin = hex2bin($split);
        $timestamp_unix = unpack('l', $bin)[1];   /////// timesatmp correct
        $timestamp_unix = $timestamp_unix - ($timestamp_unix % 60);
        $current_time = new DateTime("@$timestamp_unix");*/

        $date = new DateTime();
        $unixTime = $date->getTimestamp();
        $current_time = $date->modify('-' . ($unixTime % 60) . ' seconds');
        $last_data = $client->microcontrollerData()->latest()->first();

        if ($last_data != null){
            if ($last_data->source_timestamp == $current_time->format('Y-m-d H:i:s')){
                $this->source_timestamp = $current_time->modify('+60 seconds');
            } else{
                $this->source_timestamp = $current_time->format('Y-m-d H:i:s');
            }
            $last_raw_json = json_decode($last_data->raw_json, true);
        } else{

            $this->source_timestamp = $current_time->format('Y-m-d H:i:s');
        }

        $timestamp_unix = $current_time->getTimestamp();//delete
        $json['timestamp'] = $timestamp_unix;

        if ($json['import_wh'] == 0) {
            $json['import_VArh'] = $last_raw_json['import_VArh'];
            $json['import_VArh'] = $last_raw_json['import_VArh'];
            $json['ph1_import_kvarh'] = $last_raw_json['ph1_import_kvarh'];
            $json['ph2_import_kvarh'] = $last_raw_json['ph2_import_kvarh'];
            $json['ph3_import_kvarh'] = $last_raw_json['ph3_import_kvarh'];
            $json['ph1_import_kwh'] = $last_raw_json['ph1_import_kwh'];
            $json['ph2_import_kwh'] = $last_raw_json['ph2_import_kwh'];
            $json['ph3_import_kwh'] = $last_raw_json['ph3_import_kwh'];
        }

        if (count($client->microcontrollerData()->get()) == 0) {
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
            $reference_hour = new DateTime();
            $reference_hour->setTimestamp($timestamp_unix - ($timestamp_unix % 3600));

            $reference_data = $client->microcontrollerData()->whereBetween("source_timestamp", [$reference_hour->format('Y-m-d H:i:s'), $current_time->format('Y-m-d H:i:s')])
                ->get()->last();


            if (empty($reference_data)) {
                $json['kwh_interval'] = 0;
                $json['varh_interval'] = 0;
                $json['ph1_varCh_acumm'] = $json['ph1_varCh_acumm'] + $last_raw_json['ph1_varCh_acumm'];
                $json['ph1_varLh_acumm'] = $json['ph1_varLh_acumm'] + $last_raw_json['ph1_varLh_acumm'];
                $json['ph2_varCh_acumm'] = $json['ph2_varCh_acumm'] + $last_raw_json['ph2_varCh_acumm'];
                $json['ph2_varLh_acumm'] = $json['ph2_varLh_acumm'] + $last_raw_json['ph2_varLh_acumm'];
                $json['ph3_varCh_acumm'] = $json['ph3_varCh_acumm'] + $last_raw_json['ph3_varCh_acumm'];
                $json['ph3_varLh_acumm'] = $json['ph3_varLh_acumm'] + $last_raw_json['ph3_varLh_acumm'];
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
                $reference_data_json = json_decode($reference_data->raw_json, true);
                $json['kwh_interval'] = $json['import_wh'] - $reference_data_json['import_wh'];
                $json['varh_interval'] = $json['import_VArh'] - $reference_data_json['import_VArh'];
                $json['ph1_varCh_acumm'] = $json['ph1_varCh_acumm'] + $last_raw_json['ph1_varCh_acumm'];
                $json['ph1_varLh_acumm'] = $json['ph1_varLh_acumm'] + $last_raw_json['ph1_varLh_acumm'];
                $json['ph2_varCh_acumm'] = $json['ph2_varCh_acumm'] + $last_raw_json['ph2_varCh_acumm'];
                $json['ph2_varLh_acumm'] = $json['ph2_varLh_acumm'] + $last_raw_json['ph2_varLh_acumm'];
                $json['ph3_varCh_acumm'] = $json['ph3_varCh_acumm'] + $last_raw_json['ph3_varCh_acumm'];
                $json['ph3_varLh_acumm'] = $json['ph3_varLh_acumm'] + $last_raw_json['ph3_varLh_acumm'];
                $json['varCh_acumm'] = $json['ph1_varCh_acumm'] + $json['ph2_varCh_acumm'] + $json['ph3_varCh_acumm'];
                $json['varLh_acumm'] = $json['ph1_varLh_acumm'] + $json['ph2_varLh_acumm'] + $json['ph3_varLh_acumm'];
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
        if ($unix_time % 60 == 0) {
            HourlyMicrocontrollerData::create([
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'hour' => $hour,
                'minute' => $minute,
                'client_id' => $this->client_id,
                'microcontroller_data_id' => $this->id,
            ]);
        }
        if ($minute == 59) {
            $percent_penalizable_inductive = ($this->interval_reactive_inductive_consumption * 100) / $this->interval_real_consumption;
            if ($percent_penalizable_inductive >= 50) {
                $penalizable_inductive = ($this->interval_real_consumption * $percent_penalizable_inductive / 100) - ($this->interval_real_consumption * 0.5);
            } else {
                $penalizable_inductive = 0;
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
                'penalizable_reactive_capacitive_consumption' => $this->interval_reactive_capacitive_consumption,
                'interval_reactive_inductive_consumption' => $this->interval_reactive_inductive_consumption,
                'penalizable_reactive_inductive_consumption' => $penalizable_inductive,
            ]);
            if ($hour == 23) {
                $json = $this->raw_json;
                $data_frame = collect(config('data-frame.data_frame'));
                $accum_variable = $data_frame->where('bolean_accum', true);
                $penalizable_inductive_day = 0;
                $penalizable_capacitive_day = 0;
                $active_consumption = 0;
                $data_day = Client::find($this->client_id)->dailyMicrocontrollerData()->where('year', $year)->where('month', $month)->where('day', $day)->get();
                foreach ($accum_variable as $variable){
                    $json[$variable['variable_name']] = 0;
                    foreach ($data_day as $index => $item){
                        $raw_json = json_decode($item->raw_json, true);
                        $json[$variable['variable_name']] = $json[$variable['variable_name']] + $raw_json[$variable['variable_name']];
                        if ($index == 0){
                            $active_consumption = $active_consumption + $item->interval_real_consumption;
                            $penalizable_inductive_day = $penalizable_inductive_day + $item->penalizable_reactive_inductive_consumption;
                            $penalizable_capacitive_day = $penalizable_capacitive_day + $item->penalizable_reactive_capacitive_consumption;
                        }
                    }
                }
                MonthlyMicrocontrollerData::create([
                    'year' => $year,
                    'month' => $month,
                    'day' => $day,
                    'client_id' => $this->client_id,
                    'microcontroller_data_id' => $this->id,
                    'active_consumption' => $active_consumption,
                    'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_day,
                    'penalizable_reactive_inductive_consumption' => $penalizable_inductive_day,
                    'raw_json' => $json,
                ]);
                if ($day == $last_day_month) {
                    $json = $this->raw_json;
                    $penalizable_inductive_month = 0;
                    $penalizable_capacitive_month = 0;
                    $active_consumption = 0;
                    $data_month = Client::find($this->client_id)->monthlyMicrocontrollerData()->where('year', $year)->where('month', $month)->get();
                    foreach ($accum_variable as $variable){
                        $json[$variable['variable_name']] = 0;
                        foreach ($data_day as $index => $item){
                            $raw_json = json_decode($item->raw_json, true);
                            $json[$variable['variable_name']] = $json[$variable['variable_name']] + $raw_json[$variable['variable_name']];
                            if ($index == 0){
                                $active_consumption = $active_consumption + $item->interval_real_consumption;
                                $penalizable_inductive_day = $penalizable_inductive_day + $item->penalizable_reactive_inductive_consumption;
                                $penalizable_capacitive_day = $penalizable_capacitive_day + $item->penalizable_reactive_capacitive_consumption;
                            }
                        }
                    }
                    AnnualMicrocontrollerData::create([
                        'year' => $year,
                        'month' => $month,
                        'client_id' => $this->client_id,
                        'microcontroller_data_id' => $this->id,
                        'active_consumption' => $active_consumption,
                        'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_month,
                        'penalizable_reactive_inductive_consumption' => $penalizable_inductive_month,
                        'raw_json' => $json,
                    ]);
                }
            }
        }
    }

    private function alert()
    {
        $flags_frame = config('data-frame.flags_frame');
        $binary_flags = sprintf('%064b', $this->raw_json['flags']);
        $split = substr($binary_flags, (61), (3));
        if ($split == "100") {
            foreach ($flags_frame as $flag) {
                $split = substr($binary_flags, ($flag['index']), (1));
                if ($split == "1") {
                    if ($flag['flag_name'] == 'flagOpened') {
                        $value = 1;
                    } else {
                        $value = $this->raw_json[$flag['variable_name']];
                    }
                    AlertHistory::create([
                        'microcontroller_data_id' => $this->id,
                        'flag_index' => $flag['bit'],
                        'value' => $value,
                    ]);
                    ///notificar alerta
                }
            }
        } else {
            $percent_reactive_consumption = ($this->interval_reactive_consumption * 100) / $this->interval_real_consumption;
            if ($percent_reactive_consumption >= 50) {////50% is dinamyc value
                AlertHistory::create([
                    'microcontroller_data_id' => $this->id,
                    'flag_index' => 14,
                    'value' => $percent_reactive_consumption,
                ]);
            }
            if ($this->interval_real_consumption >= 1200) {//// 1200 es dinamyc value
                AlertHistory::create([
                    'microcontroller_data_id' => $this->id,
                    'flag_index' => 13,
                    'value' => $this->interval_real_consumption - 1200,
                ]);
            }////// notificar alertas
        }
    }
}
