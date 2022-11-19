<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataJob;
use App\Models\V1\Client;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
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
    protected $signature = 'command:name';

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
        $this->unpackData();
        $this->deleteClientRelationship();
        $first_data = MicrocontrollerData::whereNotNull('source_timestamp')
            ->whereBetween("created_at", [$this->current_time->copy()->subDay()->format('Y-m-d 00:00:00'), $this->current_time->format('Y-m-d H:i:s')])
            ->orderBy('source_timestamp')
            ->first();
        $this->start_date = new Carbon($first_data->source_timesatmp);
        $start_date_copy = $this->start_date->copy();
        while (true){
            if ($this->start_date->diffInMinutes($this->current_time) == 0){
                break;
            }
            $hour= $this->start_date->copy();
            $minute_data = MicrocontrollerData::whereNull('client_id')
                ->whereBetween("created_at", [$this->current_time->copy()->subDay()->format('Y-m-d 00:00:00'), $this->current_time->format('Y-m-d H:i:s')])
                ->whereBetween("source_timestamp", [$this->start_date->copy()->subMinute()->format('Y-m-d H:i:59'), $this->start_date->copy()->addMinute()->format('Y-m-d H:i:00')])
                ->orderBy('source_timestamp')
                ->get();
            if (count($minute_data)>0){
                $job_batch = [];
                foreach ($minute_data as $datum){
                    array_push($itemArray, new SerializeMicrocontrollerDataJob($datum));
                }
                if ($this->start_date->format('i') == '59'){
                    Bus::batch(
                        $job_batch
                    )->finally(function (Batch $batch) {
                        $date_aux = $this->start_date->copy();
                        $this->calculateConsumptionHourly($date_aux);
                    })->onQueue('reorder_data')->dispatch();
                }else{
                    Bus::batch(
                        $job_batch
                    )->onQueue('reorder_data')->dispatch();
                }
                sleep(800000);
            }
            if ($this->start_date->format('i') == '59'){
                $this->calculateConsumptionHourly($this->start_date);
            }
            $this->start_date->addMinute();
            $minute_data = [];
        }


    }
    private function unpackData(){
        $data_pack = MicrocontrollerData::whereNull('client_id')
            ->whereNotNull('source_timestamp')
            ->whereBetween("created_at", [$this->current_time->copy()->subDay()->format('Y-m-d 00:00:00'), $this->current_time->format('Y-m-d H:i:s')])
            ->orderBy('source_timestamp')->orderBy('created_at')
            ->get();
        if ($data_pack) {
            $data_frame = config('data-frame.data_frame');
            $date = Carbon::now();
            foreach ($data_pack as $i=>&$item) {
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
        }
    }
    private function deleteClientRelationship(){

        $data_pack = MicrocontrollerData::withTrashed()
            ->whereNotNull('source_timestamp')
            ->whereBetween("created_at", [$this->current_time->copy()->subDay()->format('Y-m-d 00:00:00'), $this->current_time->format('Y-m-d H:i:s')])
            ->get();
        if ($data_pack) {
            foreach ($data_pack as $item) {
                $item->restore();
                $item->client_id = null;
                $raw_json = json_decode($item->raw_json, true);
                $raw_json['ph1_varCh_acumm'] = $raw_json['data_ph1_varCh_acumm'];
                $raw_json['ph2_varCh_acumm'] = $raw_json['data_ph2_varCh_acumm'];
                $raw_json['ph3_varCh_acumm'] = $raw_json['data_ph3_varCh_acumm'];
                $raw_json['ph1_varLh_acumm'] = $raw_json['data_ph1_varLh_acumm'];
                $raw_json['ph2_varLh_acumm'] = $raw_json['data_ph2_varLh_acumm'];
                $raw_json['ph3_varLh_acumm'] = $raw_json['data_ph3_varLh_acumm'];
                $item->raw_json = $raw_json;
                $item->saveQuietly();
            }
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
