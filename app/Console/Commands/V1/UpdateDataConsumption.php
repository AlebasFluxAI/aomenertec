<?php

namespace App\Console\Commands\V1;


use App\Jobs\V1\Enertec\UnpackDataJob;
use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\StopUnpackDataClient;
use Carbon\Carbon;
use http\Client;
use Illuminate\Console\Command;

class UpdateDataConsumption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:update_data_consumption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run every five minutes recording data consumption to clients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now =new Carbon();
        $j=0;
        $i = 0;
        $queues = ['spot1', 'spot2', 'spot4', 'spot5'];
        foreach (MicrocontrollerData::select('id', 'source_timestamp', 'raw_json')
                     ->where('created_at', '>=', $now->subHours(6)->format('Y-m-d H:00:00'))
                     ->whereNull('client_id')
                     ->whereNotNull('source_timestamp')
                     ->orderBy('source_timestamp')
                     ->cursor() as $item) {
            echo $i."\n";
            $this->unPack($item);
            // dispatch(new UnpackDataJob($item))->onQueue('spot4');
            $i++;
        }

    }

    public function unPack(MicrocontrollerData $item)
    {
        $data_frame = config('data-frame.data_frame');
        $date = Carbon::now();
        $raw_json = json_decode($item->raw_json, true);
        $last_data = null;
        $client = null;
        if ($raw_json === null) {
            $decode = bin2hex(base64_decode($item->raw_json));
            $split = substr($decode, (16), (16));
            $bin = hex2bin($split);
            $equipment_serial = str_pad(unpack('Q', $bin)[1], 6, "0", STR_PAD_LEFT);
            $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                ->first();
            if ($equipment) {
                $client = $equipment->clients()->first();
                if ($client) {
                    if ($client->stopUnpackClient()->exists()) {
                        return;
                    }
                    $last_data = $client->microcontrollerData()->orderBy('source_timestamp', 'desc')->first();
                }
            }

            if (strlen($item->raw_json) > 20) {
                if ($last_data) {
                    $last_raw_json = json_decode($last_data->raw_json, true);
                }
                $source_timestamp = Carbon::create($item->source_timestamp);
                echo $source_timestamp."\n";
                if ($date->diffInDays($source_timestamp) <= 365) {
                    foreach ($data_frame as $data) {
                        try {
                            $split = substr($decode, ($data['start']), ($data['lenght']));
                            $bin = hex2bin($split);
                            if (strlen($bin) == ($data['lenght'] / 2)) {
                                if ($data['start'] >= 450) {
                                    $json[$data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                                    $json["data_" . $data['variable_name']] = (unpack($data['type'], $bin)[1]) / 1000;
                                } else {
                                    if ($data['variable_name'] == "flags") {
                                        $json[$data['variable_name']] = strval(unpack($data['type'], $bin)[1]);
                                    } else {
                                        if ($data['variable_name'] == "equipment_id") {
                                            $json[$data['variable_name']] = $equipment_serial;
                                        } else {
                                            $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                                        }
                                    }
                                }
                                if ($data['start'] >= 72) {
                                    if ($json[$data['variable_name']] < $data['min'] or $json[$data['variable_name']] > $data['max']) {
                                        if (!$data['default']) {
                                            $json[$data['variable_name']] = $data['default'];
                                        } else {
                                            if ($last_data) {
                                                if ($data['start'] >= 450) {
                                                    $json[$data['variable_name']] = $last_raw_json[$data["data_" .'variable_name']];
                                                } else {
                                                    $json[$data['variable_name']] = $last_raw_json[$data['variable_name']];
                                                }
                                            } else {
                                                $json[$data['variable_name']] = 0;
                                            }
                                        }
                                    }
                                }

                                if (is_nan($json[$data['variable_name']])) {
                                    $json[$data['variable_name']] = null;
                                }

                                if ($data['variable_name'] == "ph3_varLh_acumm") {
                                    break;
                                }
                            } else {
                                if ($data['start'] >= 72) {
                                    if (!$data['default']) {
                                        $json[$data['variable_name']] = $data['default'];
                                    } else {
                                        if ($last_data) {
                                            if (isset($last_raw_json[$data['variable_name']])) {
                                                $json[$data['variable_name']] = $last_raw_json[$data['variable_name']];
                                            } else {
                                                $json[$data['variable_name']] = 0;
                                            }
                                        } else {
                                            $json[$data['variable_name']] = 0;
                                        }
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            echo 'Excepción capturada: ', $e->getMessage(), "\n";
                        }
                    }
                    $item->raw_json = $json;

                    if ($json['import_wh'] <= 0) {
                        if ($last_data) {
                            if ($last_raw_json['import_wh']>0) {
                                $item->updateQuietly();
                                $item->forceDelete();
                                return;
                            }
                        }
                    }

                    if ($client) {
                        $this->jsonEdit(true, $item);

                        //if (!$client->stopUnpackClient()->exists()) {
                        $item->updateQuietly();

                        //dispatch(new JsonEdit($item->id, true))->onQueue($this->queue);
                        //}
                    } else{
                        $item->forceDelete();
                    }
                } else {
                    $item->forceDelete();
                }
            } else {
                //$item->forceDelete();
            }
        }else {
            $raw_json['ph1_varCh_acumm'] = $raw_json['data_ph1_varCh_acumm'] ;
            $raw_json['ph2_varCh_acumm'] = $raw_json['data_ph2_varCh_acumm'] ;
            $raw_json['ph3_varCh_acumm'] = $raw_json['data_ph3_varCh_acumm'] ;
            $raw_json['ph1_varLh_acumm'] = $raw_json['data_ph1_varLh_acumm'] ;
            $raw_json['ph2_varLh_acumm'] = $raw_json['data_ph2_varLh_acumm'] ;
            $raw_json['ph3_varLh_acumm'] = $raw_json['data_ph3_varLh_acumm'] ;
            $item->raw_json = json_encode($raw_json);
            $item->updateQuietly();
            $this->jsonEdit(true, $item);
            //$item->save();
            /*$equipment_serial = str_pad($raw_json['equipment_id'], 6, "0", STR_PAD_LEFT);
            $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)->first();
            if ($equipment) {
                $client = $equipment->clients()->first();
                if ($client) {
                    if (!$client->stopUnpackClient()->exists()) {
                        $item->save();
                        dispatch(new JsonEdit($item, true))->onQueue($this->queue);
                    }
                }
            }*/
        }
    }

    public function jsonEdit($flag, MicrocontrollerData $item)
    {
        $date = new Carbon();
        if (is_string($item->raw_json)) {
            $json = json_decode($item->raw_json, true);
            if ($json == null){
                return;
            }
        } elseif (is_array($item->raw_json)) {
            $json = $item->raw_json;
        }
        $json['ph1_varCh_acumm'] = $json['data_ph1_varCh_acumm'];
        $json['ph2_varCh_acumm'] = $json['data_ph2_varCh_acumm'];
        $json['ph3_varCh_acumm'] = $json['data_ph3_varCh_acumm'];
        $json['ph1_varLh_acumm'] = $json['data_ph1_varLh_acumm'];
        $json['ph2_varLh_acumm'] = $json['data_ph2_varLh_acumm'];
        $json['ph3_varLh_acumm'] = $json['data_ph3_varLh_acumm'];

        $timestamp_unix = $json['timestamp'];
        $current_time = $date->setTimestamp($timestamp_unix);
        $item->source_timestamp = $current_time->format('Y-m-d H:i:s');
        $equipment_serial = str_pad($json['equipment_id'], 6, "0", STR_PAD_LEFT);
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
            ->first();
        if ($equipment == null) {
            $item->delete();
            return;
        }
        $client = $equipment->clients()->first();
        if ($client == null) {
            $item->delete();
            return;
        }
        if ($flag) {
            if ($client->stopUnpackClient()->exists()) {
                return;
            }
            if (MicrocontrollerData::whereClientId($client->id)->where('source_timestamp', $current_time->format('Y-m-d H:i:s'))->exists()) {
                if ($item->hourlyMicrocontrollerData()->exists()){
                    $item->hourlyMicrocontrollerData()->forceDelete();
                }
                if ($item->dailyMicrocontrollerData()->exists()){
                    $item->dailyMicrocontrollerData()->forceDelete();
                }
                if ($item->clientAlert()->exists()){
                    $item->clientAlert()->forceDelete();
                }
                $item->delete();
                return;
            }
        }else{
            if (!$client->stopUnpackClient()->exists()) {
                StopUnpackDataClient::create(['client_id' => $client->id]);
            }
        }

        if (!MicrocontrollerData::whereClientId($client->id)->exists()) {
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

                $last_data = MicrocontrollerData::whereClientId($client->id)->orderBy('source_timestamp', 'desc')->first();


                if ($last_data) {
                    if (new Carbon($last_data->source_timestamp) >= $current_time) {
                        $item->delete();
                        return;
                    }
                    $last_raw_json = json_decode($last_data->raw_json, true);
                    if ($json['import_wh'] <= 0) {

                        if ($last_raw_json['import_wh'] > 0) {
                            $item->forceDelete();
                            return;
                        }
                    }
                }
            } else{
                $last_data = MicrocontrollerData::whereClientId($client->id)->where('source_timestamp', '<', $current_time->format('Y-m-d H:i:s'))->orderBy('source_timestamp', 'desc')->first();
                if ($last_data) {
                    if (new Carbon($last_data->source_timestamp) >= $current_time) {
                        //$item->delete();
                        return;
                    }
                    $last_raw_json = json_decode($last_data->raw_json, true);
                    if ($json['import_wh'] <= 0) {

                        if ($last_raw_json['import_wh'] > 0) {
                            $item->forceDelete();
                            return;
                        }
                    }
                } else{
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
            $reference_data = MicrocontrollerData::whereClientId($client->id)
                ->whereBetween('source_timestamp', [$reference_hour->format('Y-m-d H:00:00'), $reference_hour->format('Y-m-d H:59:59')])
                ->orderBy('source_timestamp', 'desc')
                ->first();
            if (empty($reference_data)) {
                $reference_data = MicrocontrollerData::whereClientId($client->id)->where('source_timestamp', '<', $reference_hour->format('Y-m-d H:00:00'))->orderBy('source_timestamp', 'desc')->first();
            }
            if (empty($reference_data)) {
                if ($last_data) {
                    $json['kwh_interval'] = $json['import_wh'] - $last_raw_json['import_wh'];
                    $json['varh_interval'] = $json['import_VArh'] - $last_raw_json['import_VArh'];
                    $json['varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($json['ph2_varCh_acumm']) + floatval($json['ph3_varCh_acumm']);
                    $json['varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($json['ph2_varLh_acumm']) + floatval($json['ph3_varLh_acumm']);
                    $json['ph1_varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($last_raw_json['ph1_varCh_acumm']);
                    $json['ph1_varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($last_raw_json['ph1_varLh_acumm']);
                    $json['ph2_varCh_acumm'] = floatval($json['ph2_varCh_acumm']) + floatval($last_raw_json['ph2_varCh_acumm']);
                    $json['ph2_varLh_acumm'] = floatval($json['ph2_varLh_acumm']) + floatval($last_raw_json['ph2_varLh_acumm']);
                    $json['ph3_varCh_acumm'] = floatval($json['ph3_varCh_acumm']) + floatval($last_raw_json['ph3_varCh_acumm']);
                    $json['ph3_varLh_acumm'] = floatval($json['ph3_varLh_acumm']) + floatval($last_raw_json['ph3_varLh_acumm']);
                    $json['varCh_acumm'] = $json['varCh_acumm'] + floatval($last_raw_json['varCh_acumm']);
                    $json['varLh_acumm'] = $json['varLh_acumm'] + floatval($last_raw_json['varLh_acumm']);
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
                    $json['ph1_varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($last_raw_json['ph1_varCh_acumm']);
                    $json['ph1_varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($last_raw_json['ph1_varLh_acumm']);
                    $json['ph2_varCh_acumm'] = floatval($json['ph2_varCh_acumm']) + floatval($last_raw_json['ph2_varCh_acumm']);
                    $json['ph2_varLh_acumm'] = floatval($json['ph2_varLh_acumm']) + floatval($last_raw_json['ph2_varLh_acumm']);
                    $json['ph3_varCh_acumm'] = floatval($json['ph3_varCh_acumm']) + floatval($last_raw_json['ph3_varCh_acumm']);
                    $json['ph3_varLh_acumm'] = floatval($json['ph3_varLh_acumm']) + floatval($last_raw_json['ph3_varLh_acumm']);
                    $json['varCh_acumm'] = $json['varCh_acumm'] + floatval($last_raw_json['varCh_acumm']);
                    $json['varLh_acumm'] = $json['varLh_acumm'] + floatval($last_raw_json['varLh_acumm']);
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
        $item->client_id = $client->id;
        $item->accumulated_real_consumption = $json['import_wh'];
        $item->interval_real_consumption = $json['kwh_interval'];
        $item->accumulated_reactive_consumption = $json['import_VArh'];
        $item->interval_reactive_consumption = $json['varh_interval'];
        $item->accumulated_reactive_capacitive_consumption = $json['varCh_acumm'];
        $item->accumulated_reactive_inductive_consumption = $json['varLh_acumm'];
        $item->interval_reactive_capacitive_consumption = $json['varCh_interval'];
        $item->interval_reactive_inductive_consumption = $json['varLh_interval'];
        $item->raw_json = $json;
        if (!$flag) {
            $equals = MicrocontrollerData::whereClientId($client->id)->where('source_timestamp', $current_time->format('Y-m-d H:i:s'))->get();
                if ($equals){
                    foreach ($equals as $item_aux){
                        if ($item_aux->id != $item_aux->id){
                            if ($item_aux->hourlyMicrocontrollerData()->exists()) {
                                $item_aux->hourlyMicrocontrollerData()->forceDelete();
                            }
                            if ($item_aux->dailyMicrocontrollerData()->exists()) {
                                $item_aux->dailyMicrocontrollerData()->forceDelete();
                            }
                            if ($item_aux->clientAlert()->exists()) {
                                $item_aux->clientAlert()->forceDelete();
                            }
                            $item->delete();
                            return;
                        }
                    }
                }
        }
        $item->saveQuietly();
        if ($flag) {
            dispatch(new UpdatedMicrocontrollerDataJob($item))->onQueue('spot');
            echo "antes.\n";
            $this->alertEnergyEvent($item);
            echo "despues.\n";

        }
    }

    public function alertEnergyEvent(MicrocontrollerData $item)
    {

        $binary_flags = sprintf("%064b", ($item->raw_json['flags']));
        $item->source_timestamp = new Carbon($item->source_timestamp);
        $is_wifi = substr($binary_flags, 2, 1);

        $client = \App\Models\V1\Client::find($item->client_id);
        if ($is_wifi == 1) {
            $is_wifi = true;
        } else {
            $is_wifi = false;
        }
        $offset_outputs = [0,3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
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
        $real_time_flag = $client->clientConfiguration()->first();
        $real_time_flag->real_time_flag = $is_wifi;
        $real_time_flag->save();
        $value = 0;
        $unix_time = $item->raw_json["timestamp"];
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
        $energy_hour = MicrocontrollerData::whereClientId($client->id)->whereBetween('source_timestamp', [$current_time->format('Y-m-d H:00:00'), $current_time->format('Y-m-d H:59:59')])
            ->orderBy('source_timestamp', 'desc')->first();
        $energy_month = MicrocontrollerData::whereClientId($client->id)->whereBetween('source_timestamp', [$current_time_aux->format('Y-m-1 00:00:00'), $current_time_aux->format('Y-m-t 23:59:59')])
            ->orderBy('source_timestamp', 'desc')->first();

        if (!$energy_hour) {
            $energy_hour = MicrocontrollerData::whereClientId($client->id)
                ->whereBetween('source_timestamp', [$item->source_timestamp->format('Y-m-d H:00:00'), $item->source_timestamp->format('Y-m-d H:59:59')])
                ->orderBy('source_timestamp')
                ->first();
        }
        if (!$energy_month) {
            $energy_month = MicrocontrollerData::whereClientId($client->id)
                ->whereBetween('source_timestamp', [$item->source_timestamp->format('Y-m-1 00:00:00'), $item->source_timestamp->format('Y-m-t 23:59:59')])
                ->orderBy('source_timestamp')
                ->first();
        }
        foreach ($alerts as $alert) {
            $value = $this->calculateValueAlert($item, $alert->flag_id, $energy_month, $energy_hour);
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
                    $this->createAlert($item, $value, $type, $alert);
                }
            } else {
                if ($alert->max_alert <= $value) {
                    $type = ClientAlert::ALERT;
                    $this->createAlert($item, $value, $type, $alert);
                }
            }
        }
        echo "alert\n";
    }

    public function calculateValueAlert(MicrocontrollerData $item, $flag_id, $energy_month, $energy_hour)
    {
        $value = 0;
        if($energy_month) {
            if ($flag_id == 50) {
                $value = $item->accumulated_real_consumption - $energy_month->accumulated_real_consumption;
            } elseif ($flag_id == 51) {
                $value = $item->accumulated_reactive_inductive_consumption - $energy_month->accumulated_reactive_inductive_consumption;
            } elseif ($flag_id == 52) {
                $value = $item->accumulated_reactive_capacitive_consumption - $energy_month->accumulated_reactive_capacitive_consumption;
            }
        }
        if ($energy_hour) {
            if($flag_id == 53){
                $value = $item->accumulated_real_consumption - $energy_hour->accumulated_real_consumption;
            } elseif ($flag_id == 54){
                $value = $item->accumulated_reactive_inductive_consumption - $energy_hour->accumulated_reactive_inductive_consumption;
            } elseif ($flag_id == 55){
                $value = $item->accumulated_reactive_capacitive_consumption - $energy_hour->accumulated_reactive_capacitive_consumption;
            }
        }
        if($flag_id == 56){
            if ($item->interval_real_consumption != 0) {
                $value = ($item->interval_reactive_inductive_consumption * 100) / $item->interval_real_consumption;
            } else {
                $value = 0;
            }
        }
        return $value;
    }

    private function createAlert(MicrocontrollerData $item, $value, $type, $alert)
    {
        if ($alert->flag_id == 56) {
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) use($item){
                $query->whereBetween("source_timestamp", [$item->source_timestamp->copy()->subMinutes(25)->format('Y-m-d H:i:s'), $item->source_timestamp->format('Y-m-d H:i:s')]);
            })->exists()) {
                echo "createalert.\n";
                ClientAlert::create([
                    'client_id' => $item->client_id,
                    'microcontroller_data_id' => $item->id,
                    'client_alert_configuration_id' => $alert->id,
                    'value' => $value,
                    'type' => $type,
                    'source_timestamp' => $item->source_timestamp->format('Y-m-d H:i:s')
                ]);

            }
        } elseif ($alert->flag_id == 50
            || $alert->flag_id == 51
            || $alert->flag_id == 52) {
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) use($item){
                $query->whereBetween("source_timestamp", [$item->source_timestamp->format('Y-m-1 00:00:00'), $item->source_timestamp->format('Y-m-t 23:59:59')]);
            })->exists()) {
                echo "createalert.\n";
                ClientAlert::create([
                    'client_id' => $item->client_id,
                    'microcontroller_data_id' => $item->id,
                    'client_alert_configuration_id' => $alert->id,
                    'value' => $value,
                    'type' => $type,
                    'source_timestamp' => $item->source_timestamp->format('Y-m-d H:i:s')
                ]);
            }
        } else {
            if (!$alert->clientAlerts()->whereHas('microcontrollerData', function ($query) use($item) {
                $query->whereBetween("source_timestamp", [$item->source_timestamp->format('Y-m-d H:00:00'), $item->source_timestamp->format('Y-m-d H:59:59')]);
            })->where('type', $type)->exists()) {
                echo "createalert.\n";
                ClientAlert::create([
                    'client_id' => $item->client_id,
                    'microcontroller_data_id' => $item->id,
                    'client_alert_configuration_id' => $alert->id,
                    'value' => $value,
                    'type' => $type,
                    'source_timestamp' => $item->source_timestamp->format('Y-m-d H:i:s')
                ]);

            }
        }
    }
}
