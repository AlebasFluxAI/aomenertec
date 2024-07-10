<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\HourlyMicrocontrollerData;
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
    ->whereBetween("source_timestamp", ['2021-11-04 00:00:00', '2023-11-04 00:00:00'])
    ->orderBy('source_timestamp')->orderBy('created_at')
    ->get();
    //echo count($data_pack)."\n";
    if ($data_pack) {
        $data_frame = config('data-frame.data_frame');
        $date = Carbon::now();
        $j=0;
        foreach ($data_pack as $i=>&$item) {
        //echo $i."\n";
        $raw_json = json_decode($item->raw_json, true);
            if ($raw_json == null) {
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
    /*echo $i."\n";
    echo "j= ".$j."\n";
    $j++;
}*/

        //$start_date = '2022-11-06 16:35:00';
        //$id_client = $this->argument('client');
        $clients = Client::find([106, 82, 78, 87]);
        //$clients = Client::whereNotIn('id', [1,4])->get();
        //$clients = Client::whereHasTelemetry(true)->get();
        foreach ($clients as $client) {
            echo "cliente = " . $client->id . "\n\n";
            if (!$client->stopUnpackClient()->exists()) {
                StopUnpackDataClient::create(['client_id' => $client->id]);
            }
            $equipment = $client->equipments()->where('equipment_type_id', 7)->first();
            $search = "\"equipment_id\":\"" . $equipment->serial . "\"";
            $search_1 = "\"equipment_id\":" . $equipment->serial;
            MicrocontrollerData::withTrashed()
                //->where('source_timestamp', '>', $start_date)
                //->where('client_id', $id_client)
                ->where('raw_json', 'like', '%' . $search . '%')
                ->orWhere('raw_json', 'like', '%' . $search_1 . '%')
                ->restore();
            $data = MicrocontrollerData::
            //->where('source_timestamp', '>', $start_date)
            //->where('client_id', $id_client)
            where('raw_json', 'like', '%' . $search . '%')
                ->orWhere('raw_json', 'like', '%' . $search_1 . '%')
                ->get();
            foreach ($data as $datum) {
                $datum->client_id = null;
                $datum->saveQuietly();
            }

            $data_pack = MicrocontrollerData::
            where('raw_json', 'like', '%' . $search . '%')
                ->orWhere('raw_json', 'like', '%' . $search_1 . '%')
                ->orderBy('source_timestamp')
                ->get();
            echo count($data_pack) . "\n";
            $itemArray = [];
            if ($data_pack) {
                foreach ($data_pack as $i => $item) {
                    $raw_json = json_decode($item->raw_json, true);
                    $raw_json['ph1_varCh_acumm'] = $raw_json['data_ph1_varCh_acumm'];
                    $raw_json['ph2_varCh_acumm'] = $raw_json['data_ph2_varCh_acumm'];
                    $raw_json['ph3_varCh_acumm'] = $raw_json['data_ph3_varCh_acumm'];
                    $raw_json['ph1_varLh_acumm'] = $raw_json['data_ph1_varLh_acumm'];
                    $raw_json['ph2_varLh_acumm'] = $raw_json['data_ph2_varLh_acumm'];
                    $raw_json['ph3_varLh_acumm'] = $raw_json['data_ph3_varLh_acumm'];
                    $item->raw_json = $raw_json;
                    $item->saveQuietly();
                    echo $i . "\n";
                    $this->jsonEdit($item);
                }
            }
        }
    }

    public function jsonEdit(MicrocontrollerData $data)
    {
        $json = $data->raw_json;
        $current_time = new Carbon($data->source_timestamp);
        $equipment_serial = str_pad($json['equipment_id'], 6, "0", STR_PAD_LEFT);
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
            ->first();
        if ($equipment == null) {
            $data->forceDelete();
            return;
        }
        $client = $equipment->clients()->first();

        if ($client == null) {
            $data->forceDelete();
            return;
        }

        if ($client->microcontrollerData()->where('source_timestamp', $current_time->format('Y-m-d H:i:s'))->exists()) {
            if ($data->hourlyMicrocontrollerData()->exists()) {
                $data->hourlyMicrocontrollerData()->forceDelete();
            }
            if ($data->dailyMicrocontrollerData()->exists()) {
                $data->dailyMicrocontrollerData()->forceDelete();
            }
            $data->forceDelete();
            return;
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
            $last_data = $client->microcontrollerData()->where('source_timestamp', '<', $current_time->format('Y-m-d H:i:s'))->orderBy('source_timestamp', 'desc')->first();
            $last_raw_json = json_decode($last_data->raw_json, true);
            if ($last_data) {
                $last_raw_json = json_decode($last_data->raw_json, true);
                if ($json['import_wh'] <= 0) {

                    if ($last_raw_json['import_wh'] > 0) {
                        $data->forceDelete();
                        return;
                    }
                }
                if ($json['import_wh'] < $last_raw_json['import_wh']) {
                    $json['import_wh'] = $last_raw_json['import_wh'];
                }
                if ($json['import_VArh'] < $last_raw_json['import_VArh']) {
                    $json['import_VArh'] = $last_raw_json['import_VArh'];
                }
            }
            $reference_hour = $current_time->copy()->subHour();
            $reference_data = $client->microcontrollerData()
                ->whereBetween('source_timestamp', [$reference_hour->format('Y-m-d H:00:00'), $reference_hour->format('Y-m-d H:59:59')])
                ->orderBy('source_timestamp', 'desc')
                ->first();

            if (empty($reference_data)) {
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

            } else {

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

        $data->client_id = $client->id;
        $data->accumulated_real_consumption = floatval($json['import_wh']);
        $data->interval_real_consumption = floatval($json['kwh_interval']);
        $data->accumulated_reactive_consumption = floatval($json['import_VArh']);
        $data->interval_reactive_consumption = floatval($json['varh_interval']);
        $data->accumulated_reactive_capacitive_consumption = $json['varCh_acumm'];
        $data->accumulated_reactive_inductive_consumption = $json['varLh_acumm'];
        $data->interval_reactive_capacitive_consumption = floatval($json['varCh_interval']);
        $data->interval_reactive_inductive_consumption = floatval($json['varLh_interval']);
        $data->raw_json = $json;
        $data->saveQuietly();
        if ($data->interval_real_consumption == 0) {
            $penalizable_inductive = $data->interval_reactive_inductive_consumption;
        } else {
            $percent_penalizable_inductive = ($data->interval_reactive_inductive_consumption * 100) / $data->interval_real_consumption;
            if ($percent_penalizable_inductive >= 50) {
                $penalizable_inductive = ($data->interval_real_consumption * $percent_penalizable_inductive / 100) - ($data->interval_real_consumption * 0.5);
            } else {
                $penalizable_inductive = 0;
            }
        }
        HourlyMicrocontrollerData::updateOrCreate(
            ['year' => $current_time->format('Y'),
                'month' => $current_time->format('m'),
                'day' => $current_time->format('d'),
                'hour' => $current_time->format('H'),
                'client_id' => $data->client_id],
            ['microcontroller_data_id' => $data->id,
                'interval_real_consumption' => $data->interval_real_consumption,
                'interval_reactive_capacitive_consumption' => $data->interval_reactive_capacitive_consumption,
                'interval_reactive_inductive_consumption' => $data->interval_reactive_inductive_consumption,
                'penalizable_reactive_capacitive_consumption' => $data->interval_reactive_capacitive_consumption,
                'penalizable_reactive_inductive_consumption' => $penalizable_inductive,
                'source_timestamp' => $data->source_timestamp,
                'raw_json' => json_encode($data->raw_json),
            ]
        );
    }
}
