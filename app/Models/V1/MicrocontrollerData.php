<?php

namespace App\Models\V1;

use App\Models\V1\AlertHistory;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\V1\Client;
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
        $this->updateData();
        //$this->alert();
    }

    private function updateData()
    {
        $data_frame = config('data-frame.data_frame');
        $decode = bin2hex(base64_decode($this->raw_json));
        //$decode = $this->raw_json;
        $varch = 0;
        $varih = 0;
        $split = substr($decode, 16, 16);
        $bin = hex2bin($split);
        $equipment_serial = unpack('Q', $bin)[1];
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
            ->first();
        $client = $equipment->clients->first();
        $this->client_id = $client->id;
        /*$split = substr($decode, 64, 8);
        $bin = hex2bin($split);
        $timestamp_unix = unpack('l', $bin)[1];   /////// timesatmp correct
        $timestamp_unix = $timestamp_unix - ($timestamp_unix % 60);
        $current_time = new DateTime("@$timestamp_unix");*/
        $unixTime = time();//delete
        $current_time = new DateTime();//delete
        $timestamp_unix = $unixTime - ($unixTime % 60);//delete
        $current_time->setTimestamp($timestamp_unix);//delete

        if (count($client->microcontrollerData) == 0) {
            $this->interval_real_consumption = 0;
            $this->interval_reactive_consumption = 0;
            $this->interval_reactive_inductive_consumption = 0;
            $this->interval_reactive_capacitive_consumption = 0;
        } else {
            $module = $timestamp_unix % 3600;
            if ($module < 60) {
                $previous_hour_unix = $timestamp_unix - (3600 + $module);
            } else {
                $previous_hour_unix = $timestamp_unix - $module;
            }
            $reference_hour = new DateTime("@$previous_hour_unix");
            $last_data = $client->microcontrollerData->last();
            $last_data_json = json_decode($last_data->raw_json, true);
            $reference_hour = new DateTime();
            $reference_hour->setTimestamp($previous_hour_unix);
            $reference_data = $client->microcontrollerData->whereBetween("source_timestamp", [$reference_hour->format('Y-m-d H:i:s'), $current_time->format('Y-m-d H:i:s')])
                ->first();
            $reference_data_json = json_decode($reference_data->raw_json, true);
        }
        foreach ($data_frame as $data) {
            try {
                $split = substr($decode, ($data['start']), ($data['lenght']));
                $bin = hex2bin($split);
                if ($data['start']>=440){
                    $json[$data['variable_name']] = (unpack($data['type'], $bin)[1])/1000;
                } else{
                    $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                }

                if (is_nan($json[$data['variable_name']])) {
                    $json[$data['variable_name']] = null;
                }

                if ($data['variable_name'] == "import_wh") {
                    $wh = $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "timestamp"){
                    $json[$data['variable_name']] = $timestamp_unix;
                } elseif ($data['variable_name'] == "import_VArh") {
                    $varh = $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "ph3_varLh_acumm") {
                    break;
                }
            } catch (Exception $e) {
                echo 'Excepción capturada: ', $e->getMessage(), "\n";
            }
        }



        if (count($client->microcontrollerData) == 0) {
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
            $json['varCh_interval'] = 0;
            $json['varLh_interval'] = 0;
        } else{
            if (empty($reference_data)) {
                $json['kwh_interval'] = 0;
                $json['varh_interval'] = 0;
                $json['ph1_varCh_acumm'] = $json['ph1_varCh_acumm'] + $last_data_json['ph1_varCh_acumm'];
                $json['ph1_varLh_acumm'] = $json['ph1_varLh_acumm'] + $last_data_json['ph1_varLh_acumm'];
                $json['ph2_varCh_acumm'] = $json['ph2_varCh_acumm'] + $last_data_json['ph2_varCh_acumm'];
                $json['ph2_varLh_acumm'] = $json['ph2_varLh_acumm'] + $last_data_json['ph2_varLh_acumm'];
                $json['ph3_varCh_acumm'] = $json['ph3_varCh_acumm'] + $last_data_json['ph3_varCh_acumm'];
                $json['ph3_varLh_acumm'] = $json['ph3_varLh_acumm'] + $last_data_json['ph3_varLh_acumm'];
                $json['varCh_acumm'] = $json['ph1_varCh_acumm'] + $json['ph2_varCh_acumm'] + $json['ph3_varCh_acumm'] ;
                $json['varLh_acumm'] = $json['ph1_varLh_acumm'] + $json['ph2_varLh_acumm'] + $json['ph3_varLh_acumm'];
                $json['ph1_varCh_interval'] = 0;
                $json['ph1_varLh_interval'] = 0;
                $json['ph2_varCh_interval'] = 0;
                $json['ph2_varLh_interval'] = 0;
                $json['ph3_varCh_interval'] = 0;
                $json['ph3_varLh_interval'] = 0;
                $json['varCh_interval'] = 0;
                $json['varLh_interval'] = 0;
            } else {
                $json['kwh_interval'] = $wh - $reference_data_json['import_wh'];
                $json['varh_interval'] = $varh - $reference_data_json['import_VArh'];
                $json['ph1_varCh_acumm'] = $json['ph1_varCh_acumm'] + $last_data_json['ph1_varCh_acumm'];
                $json['ph1_varLh_acumm'] = $json['ph1_varLh_acumm'] + $last_data_json['ph1_varLh_acumm'];
                $json['ph2_varCh_acumm'] = $json['ph2_varCh_acumm'] + $last_data_json['ph2_varCh_acumm'];
                $json['ph2_varLh_acumm'] = $json['ph2_varLh_acumm'] + $last_data_json['ph2_varLh_acumm'];
                $json['ph3_varCh_acumm'] = $json['ph3_varCh_acumm'] + $last_data_json['ph3_varCh_acumm'];
                $json['ph3_varLh_acumm'] = $json['ph3_varLh_acumm'] + $last_data_json['ph3_varLh_acumm'];
                $json['varCh_acumm'] = $json['ph1_varCh_acumm'] + $json['ph2_varCh_acumm'] + $json['ph3_varCh_acumm'];
                $json['varLh_acumm'] = $json['ph1_varLh_acumm'] + $json['ph2_varLh_acumm'] + $json['ph3_varLh_acumm'];
                $json['ph1_varCh_interval'] = $json['ph1_varCh_acumm'] - $reference_data_json['ph1_varCh_acumm'];
                $json['ph1_varLh_interval'] = $json['ph1_varLh_acumm'] - $reference_data_json['ph1_varLh_acumm'];
                $json['ph2_varCh_interval'] = $json['ph2_varCh_acumm'] - $reference_data_json['ph2_varCh_acumm'];
                $json['ph2_varLh_interval'] = $json['ph2_varLh_acumm'] - $reference_data_json['ph2_varLh_acumm'];
                $json['ph3_varCh_interval'] = $json['ph3_varCh_acumm'] - $reference_data_json['ph3_varCh_acumm'];
                $json['ph3_varLh_interval'] = $json['ph3_varLh_acumm'] - $reference_data_json['ph3_varLh_acumm'];
                $json['varCh_interval'] = $json['varCh_acumm'] - $reference_data_json['varCh_acumm'];
                $json['varLh_interval'] = $json['varLh_acumm'] - $reference_data_json['varLh_acumm'];
            }
        }

        $this->source_timestamp = $current_time->format('Y-m-d H:i:s');
        $this->accumulated_real_consumption = $wh;
        $this->interval_real_consumption = $json['kwh_interval'];
        $this->accumulated_reactive_consumption = $varh;
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
        $year = date("Y", $unix_time);
        $month = date("m", $unix_time);
        $day = date("d", $unix_time);
        $hour = date("H", $unix_time);
        $minute = date("i", $unix_time);
        echo "time";
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
        if ($unix_time % 3600 == 0) {

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
            ]);
        }
        if ($hour == 0 && $minute == 0) {
            MonthlyMicrocontrollerData::create([
                'year' => $year,
                'month' => $month,
                'day' => $day - 1,
                'client_id' => $this->client_id,
                'microcontroller_data_id' => $this->id,
            ]);
        }
        if ($day == 1 && $hour == 0 && $minute == 0) {
            AnnualMicrocontrollerData::create([
                'year' => $year,
                'month' => $month,
                'client_id' => $this->client_id,
                'microcontroller_data_id' => $this->id
            ]);
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
