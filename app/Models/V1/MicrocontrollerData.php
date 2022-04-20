<?php

namespace App\Models\V1;

use App\Models\V1\AlertHistory;
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
    public function intervalMiningData(){
        $unix_time = $this->raw_json["timestamp"];
        if ($unix_time%60 == 0){
            $year = date("Y", $unix_time);
            $month = date("m", $unix_time);
            $day = date("d", $unix_time);
            $hour = date("H", $unix_time);
            $minute = date("i", $unix_time);

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
        if ($unix_time%3600 == 0){
            DailyMicrocontrollerData::create([
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'hour' => $hour - 1,
                'client_id' => $this->client_id,
                'microcontroller_data_id' => $this->id
            ]);
        }
        if ($hour == 0 && $minute == 0){
            MonthlyMicrocontrollerData::create([
                'year' => $year,
                'month' => $month,
                'day' => $day - 1,
                'client_id' => $this->client_id,
                'microcontroller_data_id' => $this->id
            ]);
        }
        if ($day == 1 && $hour == 0 && $minute == 0){
            AnnualMicrocontrollerData::create([
                'year' => $year,
                'month' => $month - 1,
                'client_id' => $this->client_id,
                'microcontroller_data_id' => $this->id
            ]);
        }
    }

    private function updateData(){
        $data_frame = config('data-frame.data_frame');
        $decode = bin2hex(base64_decode($this->raw_json));
        //$decode = $this->raw_json;
        $varch = 0;
        $varih = 0;
        foreach ($data_frame as $data){
            try {
                $split = substr($decode, ($data['start']), ($data['lenght'] ));
                $bin = hex2bin($split);
                $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                if (is_nan( $json[$data['variable_name']])){
                    $json[$data['variable_name']] = null;
                }
                if ($data['variable_name'] == "equipment_id"){
                    $equipment_serial = $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "timestamp"){
                    $timestamp_unix = $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "import_wh"){
                    $wh = $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "import_VArh"){
                    $varh = $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "ph1_varih"){
                    $varih = $varih + $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "ph2_varih"){
                    $varih = $varih + $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "ph3_varih"){
                    $varih = $varih + $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "ph1_varch"){
                    $varch = $varch + $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "ph2_varch"){
                    $varch = $varch + $json[$data['variable_name']];
                } elseif ($data['variable_name'] == "ph3_varch"){
                    $varch = $varch + $json[$data['variable_name']];
                }
            } catch (Exception $e) {
                echo 'Excepción capturada: ',  $e->getMessage(), "\n";
            }
        }

        $unixTime = time();//delete
        $current_time = new \DateTime();
        $aux = $unixTime - ($unixTime%60);//delete
        $current_time->setTimestamp($aux);//$aux --> $timestamp_unix
        $json['timestamp'] = $aux;
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                            ->first();
        $client = $equipment->clients->first();
        $this->client_id= $client->id;

        if (count($client->microcontrollerData) == 0){
            $this->interval_real_consumption = 0;
            $this->interval_reactive_consumption = 0;
            $this->interval_reactive_capacitive_consumption = 0;
            $this->interval_reactive_inductive_consumption = 0;
            $this->accumulated_reactive_inductive_consumption = $varih;
            $this->accumulated_reactive_capacitive_consumption = $varch;

        } else{
            $module = $aux%3600;

            if ($module < 60) {
                $previous_hour_unix = $aux - (3600 + $module);
            } else{
                $previous_hour_unix = $aux - $module;
            }
            $last_data = $client->microcontrollerData->last();
            $this->accumulated_reactive_inductive_consumption = $last_data->accumulated_reactive_inductive_consumption + $varih;
            $this->accumulated_reactive_capacitive_consumption = $last_data->accumulated_reactive_capacitive_consumption + $varch;

            $reference_hour = new \DateTime();
            $reference_hour->setTimestamp($previous_hour_unix);
            $reference_data = $client->microcontrollerData->whereBetween("source_timestamp", [$reference_hour->format('Y-m-d H:i:s'), $current_time->format('Y-m-d H:i:s')])
                            ->first();
            if (empty($reference_data)){
                $this->interval_real_consumption = 0;
                $this->interval_reactive_consumption = 0;
                $this->interval_reactive_capacitive_consumption = 0;
                $this->interval_reactive_inductive_consumption = 0;

            } else{
                $this->interval_real_consumption = $wh - $reference_data->accumulated_real_consumption;
                $this->interval_reactive_consumption = $varh - $reference_data->accumulated_reactive_consumption;
                $this->interval_reactive_capacitive_consumption = $this->accumulated_reactive_capacitive_consumption - $reference_data->accumulated_reactive_capacitive_consumption;
                $this->interval_reactive_inductive_consumption = $this->accumulated_reactive_inductive_consumption - $reference_data->accumulated_reactive_inductive_consumption;
            }
        }
        $this->source_timestamp = $current_time->format('Y-m-d H:i:s');
        $this->accumulated_real_consumption = $wh;
        $this->accumulated_reactive_consumption = $varh;
        $this->raw_json = $json;
        $this->update();
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
