<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataJob;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\StopUnpackDataClient;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReorderDataClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:reorder_data_client
                            {client : ID client}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'reorder data client, parameter id client';

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
        /*$data_pack = MicrocontrollerData::whereNull('client_id')
            ->whereNotNull('source_timestamp')
            ->whereBetween("source_timestamp", ['2021-11-04 00:00:00', '2022-11-04 00:00:00'])
            ->orderBy('source_timestamp')->orderBy('created_at')
            ->get();
        echo count($data_pack)."\n";
        if ($data_pack) {
            $data_frame = config('data-frame.data_frame');
            $date = Carbon::now();
            $j=0;
            foreach ($data_pack as $i=>$item) {
                echo $i."\n";
                $raw_json = json_decode($item->raw_json, true);
                if ($raw_json == null) {
                    echo "j= ".$j."\n";
                    $j++;
                    if (strlen($item->raw_json) > 20) {
                        $decode = bin2hex(base64_decode($item->raw_json));
                        $split = substr($decode, (16), (16));
                        $bin = hex2bin($split);
                        $equipment_serial = str_pad(unpack('Q', $bin)[1], 6, "0", STR_PAD_LEFT);
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

                                        if (is_nan($json[$data['variable_name']])) {
                                            $json[$data['variable_name']] = null;
                                        }

                                        if ($data['variable_name'] == "ph3_varLh_acumm") {
                                            break;
                                        }
                                    }
                                } catch (Exception $e) {
                                    echo 'Excepción capturada: ', $e->getMessage(), "\n";
                                }
                            }
                            $item->raw_json = $json;
                            $item->saveQuietly();
                        } else {
                            $item->forceDelete();
                        }
                    } else {
                        $item->forceDelete();
                    }
                }
            }
            echo $i."\n";
        }*/

        $start_date = '2022-10-15 16:36:00';
        $id_client = $this->argument('client');
        $client = Client::find($id_client);
        if (!$client->stopUnpackClient()->exists()) {
            StopUnpackDataClient::create(['client_id' => $client->id]);
        }
        $equipment = $client->equipments()->where('equipment_type_id', 1)->first();
        $search = "\"equipment_id\":\"". $equipment->serial."\"";
        $search_1 = "\"equipment_id\":". $equipment->serial;
        $data = MicrocontrollerData::withTrashed()
            //->where('source_timestamp', '>', $start_date)
            ->where('client_id', $id_client)
            ->where('raw_json', 'like', '%' .$search. '%')
            ->orWhere('raw_json', 'like', '%' .$search_1. '%')
            ->get();

        echo count($data)."\n";
        foreach ($data as $i => $datum) {
            $datum->client_id = null;
            $datum->accumulated_real_consumption = null;
            $datum->interval_real_consumption = null;
            $datum->accumulated_reactive_consumption = null;
            $datum->interval_reactive_consumption = null;
            $datum->accumulated_reactive_capacitive_consumption = null;
            $datum->interval_reactive_capacitive_consumption = null;
            $datum->accumulated_reactive_inductive_consumption = null;
            $datum->interval_reactive_inductive_consumption = null;
            $datum->saveQuietly();
            if ($datum->trashed()){
                $datum->restore();
            }
        }
        $data_pack = MicrocontrollerData::whereNull('client_id')
                                    ->whereNotNull('source_timestamp')
                                    ->where('raw_json', 'like', '%' .$search. '%')
                                    ->orWhere('raw_json', 'like', '%' .$search_1. '%')
                                    ->orderBy('source_timestamp')->orderBy('created_at')
                                    ->get();
        echo count($data_pack)."\n";
        if ($data_pack) {
            foreach ($data_pack as $i => $item) {
                echo $i."\n";
                $raw_json = json_decode($item->raw_json, true);
                $raw_json['ph1_varCh_acumm'] = $raw_json['data_ph1_varCh_acumm'] ;
                $raw_json['ph2_varCh_acumm'] = $raw_json['data_ph2_varCh_acumm'] ;
                $raw_json['ph3_varCh_acumm'] = $raw_json['data_ph3_varCh_acumm'] ;
                $raw_json['ph1_varLh_acumm'] = $raw_json['data_ph1_varLh_acumm'] ;
                $raw_json['ph2_varLh_acumm'] = $raw_json['data_ph2_varLh_acumm'] ;
                $raw_json['ph3_varLh_acumm'] = $raw_json['data_ph3_varLh_acumm'] ;
                $item->raw_json = $raw_json;
                $item->saveQuietly();
                dispatch(new SerializeMicrocontrollerDataJob($item))->onQueue('reorder_data');
            }
        }
    }
}
