<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\JsonEdit;
use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataDayjob;
use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataMonthJob;
use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\Client;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\DailyMicrocontrollerData;
use App\Models\V1\EquipmentType;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\MonthlyMicrocontrollerData;
use App\Models\V1\StopUnpackDataClient;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;

class RefactorClientData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:refactor_data_client_last_day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public $current_time;
    public $start_date;
    public $date_aux;
    public function __construct()
    {
        $this->current_time = new Carbon();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clients = Client::whereHasTelemetry(true)->get();
        foreach ($clients as $client){
            if (!$client->stopUnpackClient()->exists()) {
                StopUnpackDataClient::create(['client_id' => $client->id]);
            }
        }
        $first_data = MicrocontrollerData::select('source_timestamp')
            ->whereDate("created_at", $this->current_time->copy()->subDays(2))
            ->orderBy('source_timestamp')->first();
        echo($first_data->source_timestamp);
        $this->date_aux = new Carbon($first_data->source_timestamp);
        $this->unpackData();

        $queues = ['spot1', 'spot2', 'spot3', 'spot4', 'spot5'];

        $this->start_date = new Carbon($first_data->source_timestamp);
        $start_date_copy = new Carbon($first_data->source_timestamp);
        $current_time = $this->current_time->copy();
        $end_date= new Carbon($first_data->source_timestamp);
        $end_date_copy = new Carbon($first_data->source_timestamp);
        $end_date_first = new Carbon($first_data->source_timestamp);
        $i=0;
        while (true){
            echo $this->start_date->format('Y-m-d H-i')."\n";
            $minute_data = MicrocontrollerData::select('raw_json', 'id')
                ->whereDate('source_timestamp', $this->start_date)
                ->whereTime('source_timestamp', '>=', $this->start_date->format('H:00:00'))
                ->whereTime('source_timestamp', '<=', $this->start_date->format('H:59:59'))
                ->orderBy('source_timestamp')
                ->get();
            echo "ok\n";

            if (count($minute_data)>0){
                $i=0;
                foreach ($minute_data as $datum){
                    if ($i == (count($queues))){
                        $i=0;
                    }
                    if (is_string($datum->raw_json)) {
                        $json = json_decode($datum->raw_json, true);
                        if ($json == null){
                            continue;
                        }
                    } elseif (is_array($datum->raw_json)) {
                        $json = $datum->raw_json;
                    }
                    $equipment_serial = str_pad($json['equipment_id'], 6, "0", STR_PAD_LEFT);
                    $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                        ->first();
                    if ($equipment == null) {
                        $datum->forceDelete();
                    }else{
                        $client = $equipment->clients()->first();
                        if ($client == null) {
                            $datum->forceDelete();
                        } else{
                            dispatch(new JsonEdit($datum->id, false))->onQueue($queues[$i]);
                        }
                    }
                    $i++;
                }
            }
            if ($this->start_date->diffInHours($current_time) == 0){
                break;
            }
            $this->start_date->addHour();
        }

        while (true) {
            echo $start_date_copy->format('Y-m-d H-i')."\n";
            dispatch(new SerializeMicrocontrollerDataJob($start_date_copy->format('Y-m-d H:00:00')))->onQueue('spot2');
            if ($start_date_copy->diffInHours($current_time)==0){
                break;
            }
            $start_date_copy->addHour();
        }

        while (true) {
            echo "calc day =".$end_date->format('Y-m-d')."\n";
            dispatch(new SerializeMicrocontrollerDataDayjob($end_date->format('Y-m-d H:00:00')))->onQueue('spot2');

            if ($end_date->diffInDays($this->current_time)==1){
                break;
            }
            $end_date->addDay();
        }

        $reference_date = $this->current_time->copy();
        while (true) {
            $reference_date->subDay();
            echo "calc mes =".$reference_date->format('Y-m-d')."\n";
            if ($i == (count($queues))){
                $i=0;
            }
            dispatch(new SerializeMicrocontrollerDataMonthJob($reference_date->format('Y-m-d H:00:00')))->onQueue('spot2');
            $i++;
            if ($reference_date->diffInDays($end_date_first)==0){
                break;
            }
        }
        dd("okkkk");
    }
    private function unpackData(){
        $data_frame = config('data-frame.data_frame');
        $date = Carbon::now();
        MicrocontrollerData::withTrashed()->whereNotNull('deleted_at')
        ->whereBetween("created_at", [$this->date_aux->format('Y-m-d H:00:00'), $this->current_time->format('Y-m-d H:i:s')])
            ->restore();
        $i=0;
        foreach (MicrocontrollerData::select('raw_json', 'client_id', 'source_timestamp')
                     ->whereBetween("created_at", [$this->date_aux->format('Y-m-d H:00:00'), $this->current_time->format('Y-m-d H:i:s')])
                     ->cursor() as $item) {
            echo $i."\n";
            $i++;
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
                        $item->raw_json = json_encode($json);
                        $item->saveQuietly();
                    } else {
                        $item->forceDelete();
                    }
                } else {
                    $item->forceDelete();
                }
            }
        }
    }
    private function deleteClientRelationship(){

        foreach (MicrocontrollerData::
        whereNotNull('source_timestamp')
                     ->whereBetween("created_at", [$this->current_time->copy()->subDays(5)->format('Y-m-d 00:00:00'), $this->current_time->format('Y-m-d H:i:s')])
                     ->cursor() as $item) {

            echo $item->id."\n";
            $item->client_id = null;
            $item->saveQuietly();
        }
    }
    private function calculateConsumptionHourly(Carbon $hour_ref){
        $data_frame = config('data-frame.data_frame');
        $year =  $hour_ref->format('Y');
        $month = $hour_ref->format('m');
        $day =   $hour_ref->format('d');
        $hour =  $hour_ref->format('H');
        $clients = Client::whereHasTelemetry(true)->get();
        foreach ($clients as $client) {
            if ($client->microcontrollerData()
                ->whereBetween("source_timestamp", [$hour_ref->format('Y-m-d H:00:00'), $hour_ref->format('Y-m-d H:59:59')])->exists()) {
                $reference_data = $client->microcontrollerData()
                    ->whereBetween("source_timestamp", [$hour_ref->format('Y-m-d H:00:00'), $hour_ref->format('Y-m-d H:59:59')])
                    ->orderBy('source_timestamp', 'desc')
                    ->first();

                if ($reference_data->interval_real_consumption == 0) {
                    $penalizable_inductive = $reference_data->interval_reactive_inductive_consumption;
                } else {
                    $percent_penalizable_inductive = ($reference_data->interval_reactive_inductive_consumption * 100) / $reference_data->interval_real_consumption;
                    if ($percent_penalizable_inductive >= 50) {
                        $penalizable_inductive = ($reference_data->interval_real_consumption * $percent_penalizable_inductive / 100) - ($reference_data->interval_real_consumption * 0.5);
                    } else {
                        $penalizable_inductive = 0;
                    }
                }
                HourlyMicrocontrollerData::updateOrCreate(
                    ['year' => $year,
                        'month' => $month,
                        'day' => $day,
                        'hour' => $hour,
                        'client_id' => $reference_data->client_id],
                    ['microcontroller_data_id' => $reference_data->id,
                        'interval_real_consumption' => $reference_data->interval_real_consumption,
                        'interval_reactive_capacitive_consumption' => $reference_data->interval_reactive_capacitive_consumption,
                        'interval_reactive_inductive_consumption' => $reference_data->interval_reactive_inductive_consumption,
                        'penalizable_reactive_capacitive_consumption' => $reference_data->interval_reactive_capacitive_consumption,
                        'penalizable_reactive_inductive_consumption' => $penalizable_inductive,
                        'source_timestamp' => $reference_data->source_timestamp,
                        'raw_json' => $reference_data->raw_json]
                );
            } else {
                $last_hour = $hour_ref->copy()->subHour();
                $last_data = $client->hourlyMicrocontrollerData()
                    ->where('year', $last_hour->format('Y'))
                    ->where('month', $last_hour->format('m'))
                    ->where('day', $last_hour->format('d'))
                    ->where('hour', $last_hour->format('H'))->first();
                if ($last_data) {
                    $raw_json = json_decode($last_data->raw_json, true);
                    if ($raw_json != null) {
                        foreach ($data_frame as $item) {
                            if ($item['start'] >= 72) {
                                if ($item['variable_name'] != 'Wh_calc') {
                                    if ($item['variable_name'] != 'import_wh' and $item['variable_name'] != 'export_wh' and $item['variable_name'] != 'import_VArh' and $item['variable_name'] != 'export_VArh'
                                        and $item['variable_name'] != 'ph1_import_kwh' and $item['variable_name'] != 'ph2_import_kwh' and $item['variable_name'] != 'ph3_import_kwh' and $item['variable_name'] != 'ph1_import_kvarh'
                                        and $item['variable_name'] != 'ph2_import_kvarh' and $item['variable_name'] != 'ph3_import_kvarh' and $item['variable_name'] != 'ph1_varCh_acumm' and $item['variable_name'] != 'ph2_varCh_acumm'
                                        and $item['variable_name'] != 'ph3_varCh_acumm' and $item['variable_name'] != 'ph1_varLh_acumm' and $item['variable_name'] != 'ph2_varLh_acumm' and $item['variable_name'] != 'ph3_varLh_acumm'
                                        and $item['variable_name'] != 'varLh_acumm' and $item['variable_name'] != 'varCh_acumm'
                                    ) {
                                        if (array_key_exists($item['variable_name'], $raw_json)) {
                                            if ($raw_json[$item['variable_name']] != null) {
                                                $raw_json[$item['variable_name']] = 0;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $raw_json['data_ph1_varCh_acumm'] = 0;
                        $raw_json['data_ph2_varCh_acumm'] = 0;
                        $raw_json['data_ph3_varCh_acumm'] = 0;
                        $raw_json['data_ph1_varLh_acumm'] = 0;
                        $raw_json['data_ph2_varLh_acumm'] = 0;
                        $raw_json['data_ph3_varLh_acumm'] = 0;
                        $source_timestamp = new Carbon($last_data->source_timestamp);
                        HourlyMicrocontrollerData::updateOrCreate(
                            ['year' => $year,
                                'month' => $month,
                                'day' => $day,
                                'hour' => $hour,
                                'client_id' => $client->id],
                            ['microcontroller_data_id' => $last_data->microcontroller_data_id,
                                'interval_real_consumption' => 0,
                                'interval_reactive_capacitive_consumption' => 0,
                                'interval_reactive_inductive_consumption' => 0,
                                'penalizable_reactive_capacitive_consumption' => 0,
                                'penalizable_reactive_inductive_consumption' => 0,
                                'source_timestamp' => $source_timestamp->addHour(),
                                'raw_json' => json_encode($raw_json),
                            ]
                        );
                    }
                }
            }
        }
    }
    private function jsonEdit(MicrocontrollerData $data)
    {
        if (is_string($data->raw_json)) {
            $json = json_decode($data->raw_json, true);
            if ($json == null){
                return;
            }
        } elseif (is_array($data->raw_json)) {
            $json = $data->raw_json;
        }
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
        if (!$client->stopUnpackClient()->exists()) {
            StopUnpackDataClient::create(['client_id' => $client->id]);
        }

        if ($client->microcontrollerData()->where('source_timestamp', $current_time->format('Y-m-d H:i:s'))->exists()) {
            if ($data->hourlyMicrocontrollerData()->exists()){
                $data->hourlyMicrocontrollerData()->forceDelete();
            }
            if ($data->dailyMicrocontrollerData()->exists()){
                $data->dailyMicrocontrollerData()->forceDelete();
            }
            if ($data->clientAlert()->exists()){
                $data->clientAlert()->forceDelete();
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
                $reference_data = $client->microcontrollerData()->where('source_timestamp', '<', $reference_hour->format('Y-m-d H:00:00'))->orderBy('source_timestamp', 'desc')->first();
            }
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

    }
}
