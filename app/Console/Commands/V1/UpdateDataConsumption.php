<?php

namespace App\Console\Commands\V1;


use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
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
        $data_pack = MicrocontrollerData::whereNull('client_id')
           ->whereNotNull('source_timestamp')
            ->orderBy('source_timestamp')
            ->get();
        if ($data_pack) {
            echo count($data_pack)."\n";
            $data_frame = config('data-frame.data_frame');
            $date = Carbon::now();
            foreach ($data_pack as $i => $item) {
                echo $i."\n";
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
                                continue;
                            }
                            $last_data = $client->microcontrollerData()->orderBy('source_timestamp', 'desc')->first();
                        }
                    }

                    if (strlen($item->raw_json) > 20) {
                        if ($last_data) {
                            $last_raw_json = json_decode($last_data->raw_json, true);
                        }
                        $source_timestamp = Carbon::create($item->source_timestamp);
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
                                        continue;
                                    }
                                }
                            }

                            if ($client) {
                                if (!$client->stopUnpackClient()->exists()) {
                                    $item->save();
                                }
                            } else{
                                $item->forceDelete();
                            }
                        } else {
                            $item->forceDelete();
                        }
                    } else {
                        $item->forceDelete();
                    }


                }else {
                    $raw_json['ph1_varCh_acumm'] = $raw_json['data_ph1_varCh_acumm'] ;
                    $raw_json['ph2_varCh_acumm'] = $raw_json['data_ph2_varCh_acumm'] ;
                    $raw_json['ph3_varCh_acumm'] = $raw_json['data_ph3_varCh_acumm'] ;
                    $raw_json['ph1_varLh_acumm'] = $raw_json['data_ph1_varLh_acumm'] ;
                    $raw_json['ph2_varLh_acumm'] = $raw_json['data_ph2_varLh_acumm'] ;
                    $raw_json['ph3_varLh_acumm'] = $raw_json['data_ph3_varLh_acumm'] ;
                    $item->raw_json = json_encode($raw_json);
                    //$item->save();
                    $equipment_serial = str_pad($raw_json['equipment_id'], 6, "0", STR_PAD_LEFT);
                    $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)->first();
                    if ($equipment) {
                        $client = $equipment->clients()->first();
                        if ($client) {
                            if (!$client->stopUnpackClient()->exists()) {
                                $item->save();
                            }
                        }
                    }
                }
            }
        }
    }
}
