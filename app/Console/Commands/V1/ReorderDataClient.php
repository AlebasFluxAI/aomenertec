<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataJob;
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
        //$start_date = '2022-11-06 16:35:00';
        //$id_client = $this->argument('client');
        //$client = Client::find($id_client);
        $clients = Client::whereMotIn('id', [1,4]);
        foreach ($clients as $client) {
            echo $client->id."\n";
            /*if (!$client->stopUnpackClient()->exists()) {
                StopUnpackDataClient::create(['client_id' => $client->id]);
            }*/
            $equipment = $client->equipments()->where('equipment_type_id', 1)->first();
            $search = "\"equipment_id\":\"" . $equipment->serial . "\"";
            $search_1 = "\"equipment_id\":" . $equipment->serial;
            $data = MicrocontrollerData::withTrashed()
                //->where('source_timestamp', '>', $start_date)
                //->where('client_id', $id_client)
                ->where('raw_json', 'like', '%' . $search . '%')
                ->orWhere('raw_json', 'like', '%' . $search_1 . '%')
                ->get();


            foreach ($data as $datum) {
                $datum->restore();
                $datum->client_id = null;
                $datum->saveQuietly();
            }

            $data_pack = MicrocontrollerData::
            where('raw_json', 'like', '%' . $search . '%')
                ->orWhere('raw_json', 'like', '%' . $search_1 . '%')
                ->orderBy('source_timestamp')
                ->get();
            if ($data_pack) {
                foreach ($data_pack as  $item) {
                    $raw_json = json_decode($item->raw_json, true);
                    $raw_json['ph1_varCh_acumm'] = $raw_json['data_ph1_varCh_acumm'];
                    $raw_json['ph2_varCh_acumm'] = $raw_json['data_ph2_varCh_acumm'];
                    $raw_json['ph3_varCh_acumm'] = $raw_json['data_ph3_varCh_acumm'];
                    $raw_json['ph1_varLh_acumm'] = $raw_json['data_ph1_varLh_acumm'];
                    $raw_json['ph2_varLh_acumm'] = $raw_json['data_ph2_varLh_acumm'];
                    $raw_json['ph3_varLh_acumm'] = $raw_json['data_ph3_varLh_acumm'];
                    $item->raw_json = $raw_json;
                    $item->saveQuietly();
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
            $last_data = $client->microcontrollerData()->where('source_timestamp', '<', $current_time->format('Y-m-d H:00:00'))->orderBy('source_timestamp', 'desc')->first();
            if ($last_data) {
                $last_raw_json = json_decode($last_data->raw_json, true);
                if ($json['import_wh'] <= 0) {

                    if ($last_raw_json['import_wh']>0) {
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
                if ($last_data != null) {
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
                if ($last_data != null) {
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
                'raw_json' => $data->raw_json,
            ]
        );
    }
}
