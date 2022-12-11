<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\JsonEdit;
use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataJob;
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
        $this->unpackData();
        $this->deleteClientRelationship();
        $first_data = MicrocontrollerData::whereNotNull('source_timestamp')
            ->whereBetween("created_at", [$this->current_time->copy()->subDays(2)->format('Y-m-d 00:00:00'), $this->current_time->format('Y-m-d H:i:s')])
            ->orderBy('source_timestamp')
            ->first();
        $this->start_date = new Carbon($first_data->source_timestamp);
        $start_date_copy = new Carbon($first_data->source_timestamp);
        $current_time = $this->current_time->copy();
        $end_date= new Carbon($first_data->source_timestamp);
        $end_date_copy = new Carbon($first_data->source_timestamp);
        $end_date_first = new Carbon($first_data->source_timestamp);
        while (true){
            if ($this->start_date->diffInHours($current_time) == 0){
                break;
            }
            echo $this->start_date->format('Y-m-d H-i')."\n";
            $minute_data = MicrocontrollerData::whereNotNull('source_timestamp')
                ->whereNull('client_id')
                ->whereBetween("source_timestamp", [$this->start_date->format('Y-m-d H:00:00'), $this->start_date->format('Y-m-d H:59:59')])
                ->orderBy('source_timestamp')
                ->get();
            if (count($minute_data)>0){
                foreach ($minute_data as $datum){
                    dispatch(new JsonEdit($datum, false))->onQueue('spot');
                }
            }
            dispatch(new SerializeMicrocontrollerDataJob($this->start_date->format('Y-m-d H:00:00')))->onQueue('spot');
            $this->start_date->addHour();
            $minute_data = [];
        }
        while (true) {
            $current_time->subHour();
            echo "prom_hour".$current_time->format('Y-m-d H-i')."\n";

            foreach ($clients as $client) {
                $year =  $current_time->format('Y');
                $month = $current_time->format('m');
                $day =   $current_time->format('d');
                $hour =  $current_time->format('H');
                $hour_data =$client->hourlyMicrocontrollerdata()
                    ->where('year', $year)
                    ->where('month',$month)
                    ->where('day', $day)
                    ->where('hour', $hour)
                    ->first();
                if ($hour_data) {
                    $last_raw_json = json_decode($hour_data->raw_json, true);
                    $previous_hour_data = $client->hourlyMicrocontrollerdata()
                        ->whereBetween('source_timestamp', [$current_time->copy()->subHour()->format('Y-m-d H:00:00'), $current_time->copy()->subHour()->format('Y-m-d H:59:59')])
                        ->first();
                    if ($previous_hour_data){
                        if ($previous_hour_data->interval_real_consumption == 0){
                            $data = HourlyMicrocontrollerData::whereMicrocontrollerDataId($previous_hour_data->microcontroller_data_id)->orderBy('source_timestamp')->get();
                            if (count($data) > 1){
                                $i=0;
                                foreach ($data as $datum){
                                    if ($i == 0){
                                        $first_raw_json = json_decode($datum->raw_json, true);
                                        $average_accumulated_real_consumption = ($last_raw_json['import_wh'] - $first_raw_json['import_wh'])/count($data);
                                        $average_accumulated_real_consumption_ph1 = ($last_raw_json['ph1_import_kwh'] - $first_raw_json['ph1_import_kwh'])/count($data);
                                        $average_accumulated_real_consumption_ph2 = ($last_raw_json['ph2_import_kwh'] - $first_raw_json['ph2_import_kwh'])/count($data);
                                        $average_accumulated_real_consumption_ph3 = ($last_raw_json['ph3_import_kwh'] - $first_raw_json['ph3_import_kwh'])/count($data);
                                        $average_accumulated_reactive_consumption = ($last_raw_json['import_VArh'] - $first_raw_json['import_VArh'])/count($data);
                                        $average_accumulated_reactive_consumption_ph1 = ($last_raw_json['ph1_import_kvarh'] - $first_raw_json['ph1_import_kvarh'])/count($data);
                                        $average_accumulated_reactive_consumption_ph2 = ($last_raw_json['ph2_import_kvarh'] - $first_raw_json['ph2_import_kvarh'])/count($data);
                                        $average_accumulated_reactive_consumption_ph3 = ($last_raw_json['ph3_import_kvarh'] - $first_raw_json['ph3_import_kvarh'])/count($data);
                                    } else{
                                        $raw_json = json_decode($datum->raw_json, true);
                                        $raw_json['import_wh'] = $first_raw_json['import_wh'] + ($average_accumulated_real_consumption * $i);
                                        $raw_json['kwh_interval'] = $average_accumulated_real_consumption;
                                        $raw_json['ph1_import_kwh'] = $first_raw_json['ph1_import_kwh'] + ($average_accumulated_real_consumption_ph1 * $i);
                                        $raw_json['ph2_import_kwh'] = $first_raw_json['ph2_import_kwh'] + ($average_accumulated_real_consumption_ph2 * $i);
                                        $raw_json['ph3_import_kwh'] = $first_raw_json['ph3_import_kwh'] + ($average_accumulated_real_consumption_ph3 * $i);
                                        $raw_json['ph1_kwh_interval'] = $average_accumulated_real_consumption_ph1;
                                        $raw_json['ph2_kwh_interval'] = $average_accumulated_real_consumption_ph2;
                                        $raw_json['ph3_kwh_interval'] = $average_accumulated_real_consumption_ph3;
                                        $raw_json['import_VArh'] = $first_raw_json['import_VArh'] + ($average_accumulated_reactive_consumption * $i);
                                        $raw_json['varh_interval'] = $average_accumulated_reactive_consumption;
                                        $raw_json['ph1_import_kvarh'] = $first_raw_json['ph1_import_kvarh'] + ($average_accumulated_reactive_consumption_ph1 * $i);
                                        $raw_json['ph2_import_kvarh'] = $first_raw_json['ph2_import_kvarh'] + ($average_accumulated_reactive_consumption_ph2 * $i);
                                        $raw_json['ph3_import_kvarh'] = $first_raw_json['ph3_import_kvarh'] + ($average_accumulated_reactive_consumption_ph3 * $i);
                                        $raw_json['ph1_varh_interval'] = $average_accumulated_reactive_consumption_ph1;
                                        $raw_json['ph2_varh_interval'] = $average_accumulated_reactive_consumption_ph2;
                                        $raw_json['ph3_varh_interval'] = $average_accumulated_reactive_consumption_ph3;
                                        $datum->raw_json = json_encode($raw_json);
                                        $datum->interval_real_consumption = $raw_json['kwh_interval'];
                                        $datum->save();
                                    }
                                    $i++;
                                }
                                $last_raw_json['kwh_interval'] = $average_accumulated_real_consumption;
                                $last_raw_json['ph1_kwh_interval'] = $average_accumulated_real_consumption_ph1;
                                $last_raw_json['ph2_kwh_interval'] = $average_accumulated_real_consumption_ph2;
                                $last_raw_json['ph3_kwh_interval'] = $average_accumulated_real_consumption_ph3;
                                $last_raw_json['varh_interval'] = $average_accumulated_reactive_consumption;
                                $last_raw_json['ph1_varh_interval'] = $average_accumulated_reactive_consumption_ph1;
                                $last_raw_json['ph2_varh_interval'] = $average_accumulated_reactive_consumption_ph2;
                                $last_raw_json['ph3_varh_interval'] = $average_accumulated_reactive_consumption_ph3;
                                $hour_data->raw_json = json_encode($raw_json);
                                $hour_data->interval_real_consumption = $raw_json['kwh_interval'];
                                $hour_data->save();
                            }
                        }
                    }
                }
            }
            if ($start_date_copy->diffInHours($current_time)==0){
                break;
            }
        }

        $data_frame = config('data-frame.data_frame');
        while (true) {

            echo "calc day =".$end_date->format('Y-m-d')."\n";
            foreach ($clients as $client) {

                if ($client->microcontrollerData()
                    ->whereBetween('source_timestamp', [$end_date->format('Y-m-d 00:00:00'), $end_date->format('Y-m-d 23:59:59')])->exists()) {
                    $data_day = $client->hourlyMicrocontrollerData()
                        ->where('year', $end_date->format('Y'))
                        ->where('month', $end_date->format('m'))
                        ->where('day', $end_date->format('d'))->get();
                    $reference_data = $client->microcontrollerData()
                        ->whereBetween('source_timestamp', [$end_date->format('Y-m-d 00:00:00'), $end_date->format('Y-m-d 23:59:59')])
                        ->orderBy('source_timestamp', 'desc')
                        ->first();

                    if ($client->microcontrollerData()
                        ->whereBetween('source_timestamp', [$end_date->copy()->subDay()->format('Y-m-d 00:00:00'), $end_date->copy()->subDay()->format('Y-m-d 23:59:59')])->exists()){
                        $reference_data_first = $client->microcontrollerData()
                            ->whereBetween('source_timestamp', [$end_date->copy()->subDay()->format('Y-m-d 00:00:00'), $end_date->copy()->subDay()->format('Y-m-d 23:59:59')])
                            ->orderBy('source_timestamp', 'desc')
                            ->first();
                    } else{
                        if($client->microcontrollerData()
                            ->where('source_timestamp', '<', $end_date->format('Y-m-d 00:00:00'))->exists()) {
                            $reference_data_first = $client->microcontrollerData()
                                ->where('source_timestamp', '<', $end_date->format('Y-m-d 00:00:00'))
                                ->orderBy('source_timestamp', 'desc')
                                ->first();
                        } else{
                            $reference_data_first = $client->microcontrollerData()
                                ->whereBetween('source_timestamp', [$end_date->format('Y-m-d 00:00:00'), $end_date->format('Y-m-d 23:59:59')])
                                ->orderBy('source_timestamp')
                                ->first();
                        }
                    }
                    if ($reference_data) {
                        $json = json_decode($reference_data->raw_json, true);
                        $penalizable_inductive_day = 0;
                        $penalizable_capacitive_day = 0;
                        $interval_active_day = $reference_data->accumulated_real_consumption - $reference_data_first->accumulated_real_consumption;
                        $interval_capacitive_day = $reference_data->accumulated_reactive_capacitive_consumption - $reference_data_first->accumulated_reactive_capacitive_consumption;
                        $interval_inductive_day = $reference_data->accumulated_reactive_inductive_consumption - $reference_data_first->accumulated_reactive_inductive_consumption;
                        foreach ($data_day as $item) {
                            $penalizable_inductive_day = $penalizable_inductive_day + $item->penalizable_reactive_inductive_consumption;
                            $penalizable_capacitive_day = $penalizable_capacitive_day + $item->penalizable_reactive_capacitive_consumption;
                        }
                        $json_first = json_decode($reference_data_first->raw_json, true);
                        $json['kwh_interval'] = $json['import_wh'] - $json_first['import_wh'];
                        $json['ph1_kwh_interval'] = $json['ph1_import_kwh'] - $json_first['ph1_import_kwh'];
                        $json['ph2_kwh_interval'] = $json['ph2_import_kwh'] - $json_first['ph2_import_kwh'];
                        $json['ph3_kwh_interval'] = $json['ph3_import_kwh'] - $json_first['ph3_import_kwh'];
                        $json['varh_interval'] = $json['import_VArh'] - $json_first['import_VArh'];
                        $json['ph1_varh_interval'] = $json['ph1_import_kvarh'] - $json_first['ph1_import_kvarh'];
                        $json['ph2_varh_interval'] = $json['ph2_import_kvarh'] - $json_first['ph2_import_kvarh'];
                        $json['ph3_varh_interval'] = $json['ph3_import_kvarh'] - $json_first['ph3_import_kvarh'];
                        $json['varCh_interval'] = $json['varCh_acumm'] - $json_first['varCh_acumm'];
                        $json['ph1_varCh_interval'] = $json['ph1_varCh_acumm'] - $json_first['ph1_varCh_acumm'];
                        $json['ph2_varCh_interval'] = $json['ph2_varCh_acumm'] - $json_first['ph2_varCh_acumm'];
                        $json['ph3_varCh_interval'] = $json['ph3_varCh_acumm'] - $json_first['ph3_varCh_acumm'];
                        $json['varLh_interval'] = $json['varLh_acumm'] - $json_first['varLh_acumm'];
                        $json['ph1_varLh_interval'] = $json['ph1_varLh_acumm'] - $json_first['ph1_varLh_acumm'];
                        $json['ph2_varLh_interval'] = $json['ph2_varLh_acumm'] - $json_first['ph2_varLh_acumm'];
                        $json['ph3_varLh_interval'] = $json['ph3_varLh_acumm'] - $json_first['ph3_varLh_acumm'];

                        DailyMicrocontrollerData::updateOrCreate(
                            [
                                'year' => $end_date->format('Y'),
                                'month' => $end_date->format('m'),
                                'day' => $end_date->format('d'),
                                'client_id' => $client->id],
                            ['microcontroller_data_id' => $reference_data->id,
                                'interval_real_consumption' => $interval_active_day,
                                'interval_reactive_capacitive_consumption' => $interval_capacitive_day,
                                'interval_reactive_inductive_consumption' => $interval_inductive_day,
                                'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_day,
                                'penalizable_reactive_inductive_consumption' => $penalizable_inductive_day,
                                'raw_json' => json_encode($json)
                            ]);
                    }
                } else {
                    $last_day = $end_date->copy()->subDay();
                    $last_data = $client->hourlyMicrocontrollerData()
                        ->where('year', $last_day->format('Y'))
                        ->where('month', $last_day->format('m'))
                        ->where('day', $last_day->format('d'))->first();
                    if ($last_data) {
                        $raw_json = json_decode($last_data->raw_json, true);
                        foreach ($data_frame as $item){
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

                        DailyMicrocontrollerData::updateOrCreate(
                            ['year' => $end_date->format('Y'),
                                'month' => $end_date->format('m'),
                                'day' => $end_date->format('d'),
                                'client_id' => $client->id],
                            ['microcontroller_data_id' => $last_data->microcontroller_data_id,
                                'interval_real_consumption' => 0,
                                'interval_reactive_capacitive_consumption' => 0,
                                'interval_reactive_inductive_consumption' => 0,
                                'penalizable_reactive_capacitive_consumption' => 0,
                                'penalizable_reactive_inductive_consumption' => 0,
                                'raw_json' => json_encode($raw_json),
                            ]
                        );
                    }
                }
            }
            if ($end_date->diffInDays($this->current_time)==1){
                break;
            }
            $end_date->addDay();
        }
        $current_time = $this->current_time->copy();;
        while (true) {
            $current_time->subDay();
            echo "prom day =".$current_time->format('Y-m-d')."\n";

            foreach ($clients as $client) {
                $year =  $current_time->format('Y');
                $month = $current_time->format('m');
                $day =   $current_time->format('d');
                $hour =  $current_time->format('H');
                $day_data =$client->dailyMicrocontrollerdata()
                    ->where('year', $year)
                    ->where('month',$month)
                    ->where('day', $day)
                    ->first();
                if ($day_data) {
                    if ($day_data->interval_real_consumption != 0) {
                        $last_raw_json = json_decode($day_data->raw_json, true);
                        $previous_day_data = $client->dailyMicrocontrollerdata()
                            ->where('year', $current_time->copy()->subDay()->format('Y'))
                            ->where('month', $current_time->copy()->subDay()->format('m'))
                            ->where('day', $current_time->copy()->subDay()->format('d'))
                            ->first();
                        if ($previous_day_data) {
                            if ($previous_day_data->interval_real_consumption == 0) {
                                $data = DailyMicrocontrollerData::whereMicrocontrollerDataId($previous_day_data->microcontroller_data_id)->orderBy('year')->orderBy('month')->orderBy('day')->get();
                                if (count($data) > 1) {
                                    $i = 0;
                                    foreach ($data as $datum) {
                                        if ($i == 0) {
                                            $first_raw_json = json_decode($datum->raw_json, true);
                                            $average_accumulated_real_consumption = ($last_raw_json['import_wh'] - $first_raw_json['import_wh']) / count($data);
                                            $average_accumulated_real_consumption_ph1 = ($last_raw_json['ph1_import_kwh'] - $first_raw_json['ph1_import_kwh']) / count($data);
                                            $average_accumulated_real_consumption_ph2 = ($last_raw_json['ph2_import_kwh'] - $first_raw_json['ph2_import_kwh']) / count($data);
                                            $average_accumulated_real_consumption_ph3 = ($last_raw_json['ph3_import_kwh'] - $first_raw_json['ph3_import_kwh']) / count($data);
                                            $average_accumulated_reactive_consumption = ($last_raw_json['import_VArh'] - $first_raw_json['import_VArh']) / count($data);
                                            $average_accumulated_reactive_consumption_ph1 = ($last_raw_json['ph1_import_kvarh'] - $first_raw_json['ph1_import_kvarh']) / count($data);
                                            $average_accumulated_reactive_consumption_ph2 = ($last_raw_json['ph2_import_kvarh'] - $first_raw_json['ph2_import_kvarh']) / count($data);
                                            $average_accumulated_reactive_consumption_ph3 = ($last_raw_json['ph3_import_kvarh'] - $first_raw_json['ph3_import_kvarh']) / count($data);
                                        } else {
                                            $raw_json = json_decode($datum->raw_json, true);
                                            $raw_json['import_wh'] = $first_raw_json['import_wh'] + ($average_accumulated_real_consumption * $i);
                                            $raw_json['kwh_interval'] = $average_accumulated_real_consumption;
                                            $raw_json['ph1_import_kwh'] = $first_raw_json['ph1_import_kwh'] + ($average_accumulated_real_consumption_ph1 * $i);
                                            $raw_json['ph2_import_kwh'] = $first_raw_json['ph2_import_kwh'] + ($average_accumulated_real_consumption_ph2 * $i);
                                            $raw_json['ph3_import_kwh'] = $first_raw_json['ph3_import_kwh'] + ($average_accumulated_real_consumption_ph3 * $i);
                                            $raw_json['ph1_kwh_interval'] = $average_accumulated_real_consumption_ph1;
                                            $raw_json['ph2_kwh_interval'] = $average_accumulated_real_consumption_ph2;
                                            $raw_json['ph3_kwh_interval'] = $average_accumulated_real_consumption_ph3;
                                            $raw_json['import_VArh'] = $first_raw_json['import_VArh'] + ($average_accumulated_reactive_consumption * $i);
                                            $raw_json['varh_interval'] = $average_accumulated_reactive_consumption;
                                            $raw_json['ph1_import_kvarh'] = $first_raw_json['ph1_import_kvarh'] + ($average_accumulated_reactive_consumption_ph1 * $i);
                                            $raw_json['ph2_import_kvarh'] = $first_raw_json['ph2_import_kvarh'] + ($average_accumulated_reactive_consumption_ph2 * $i);
                                            $raw_json['ph3_import_kvarh'] = $first_raw_json['ph3_import_kvarh'] + ($average_accumulated_reactive_consumption_ph3 * $i);
                                            $raw_json['ph1_varh_interval'] = $average_accumulated_reactive_consumption_ph1;
                                            $raw_json['ph2_varh_interval'] = $average_accumulated_reactive_consumption_ph2;
                                            $raw_json['ph3_varh_interval'] = $average_accumulated_reactive_consumption_ph3;
                                            $datum->raw_json = json_encode($raw_json);
                                            $datum->interval_real_consumption = $raw_json['kwh_interval'];
                                            $datum->save();
                                        }
                                        $i++;
                                    }
                                    $last_raw_json['kwh_interval'] = $average_accumulated_real_consumption;
                                    $last_raw_json['ph1_kwh_interval'] = $average_accumulated_real_consumption_ph1;
                                    $last_raw_json['ph2_kwh_interval'] = $average_accumulated_real_consumption_ph2;
                                    $last_raw_json['ph3_kwh_interval'] = $average_accumulated_real_consumption_ph3;
                                    $last_raw_json['varh_interval'] = $average_accumulated_reactive_consumption;
                                    $last_raw_json['ph1_varh_interval'] = $average_accumulated_reactive_consumption_ph1;
                                    $last_raw_json['ph2_varh_interval'] = $average_accumulated_reactive_consumption_ph2;
                                    $last_raw_json['ph3_varh_interval'] = $average_accumulated_reactive_consumption_ph3;
                                    $day_data->raw_json = json_encode($raw_json);
                                    $day_data->interval_real_consumption = $raw_json['kwh_interval'];
                                    $day_data->save();
                                }
                            }
                        }
                    }
                }
            }
            if ($end_date_copy->diffInDays($current_time)==0){
                break;
            }
        }

        $reference_date = $this->current_time->copy();
        while (true) {
            $reference_date->subDay();
            echo "calc mes =".$reference_date->format('Y-m-d')."\n";

            $billing_day = $reference_date->format('d');
            $billing_day_clients = ClientConfiguration::whereBillingDay($billing_day)->get()->pluck('client_id');
            $clients_aux = Client::find($billing_day_clients);
            $clients = $clients_aux->where('has_telemetry', true)->all();
            if (count($clients)>0) {
                foreach ($clients as $client_aux) {
                    $client = Client::find($client_aux->id);
                    if ($reference_date->format('m') == '01') {
                        $month_aux = 12;
                        $year_aux = $reference_date->format('Y') - 1;
                    } else {
                        $month_aux = $reference_date->format('m') - 1;
                        if ($month_aux<10) {
                            $month_aux = '0'.$month_aux;
                        }
                        $year_aux = $reference_date->format('Y');
                    }
                    $start_date = Carbon::create($year_aux, $month_aux, ($billing_day + 1));
                    $end_date = Carbon::create($reference_date->format('Y'), $reference_date->format('m'), $billing_day, "23", "59", 59);

                    $data_aux = $client->dailyMicrocontrollerData()
                        ->where('year', $year_aux)
                        ->where('month', ($month_aux))
                        ->whereBetween('day', ['0' . ($billing_day + 1), ($start_date->format('t'))]);
                    $data_month = $client->dailyMicrocontrollerData()
                        ->where('year', $reference_date->format('Y'))
                        ->where('month', $reference_date->format('m'))
                        ->whereBetween('day', ['01', $billing_day])
                        ->union($data_aux)
                        ->get();


                    if (count($data_month) > 0) {
                        $end_data = $client->microcontrollerData()
                            ->whereBetween('source_timestamp', [$start_date->format('Y-m-d 00:00:00'), $end_date->format('Y-m-d 23:59:59')])
                            ->orderBy('source_timestamp', 'desc')
                            ->first();
                        $start_data = $client->microcontrollerData()
                            ->whereBetween('source_timestamp', [$start_date->format('Y-m-d 00:00:00'), $end_date->format('Y-m-d 23:59:59')])
                            ->orderBy('source_timestamp')
                            ->first();
                        if ($end_data) {
                            $reference_data = $end_data->dailyMicrocontrollerData;
                            $json = json_decode($reference_data->raw_json, true);
                            $penalizable_inductive_month = 0;
                            $penalizable_capacitive_month = 0;
                            $interval_active_month = $end_data->accumulated_real_consumption - $start_data->accumulated_real_consumption;
                            $interval_capacitive_month = $end_data->accumulated_reactive_capacitive_consumption - $start_data->accumulated_reactive_capacitive_consumption;
                            $interval_inductive_month = $end_data->accumulated_reactive_inductive_consumption - $start_data->accumulated_reactive_inductive_consumption;
                            foreach ($data_month as $item) {
                                $penalizable_inductive_month = $penalizable_inductive_month + $item->penalizable_reactive_inductive_consumption;
                                $penalizable_capacitive_month = $penalizable_capacitive_month + $item->penalizable_reactive_capacitive_consumption;
                            }
                            $json_first = json_decode($start_data->raw_json, true);
                            $json['kwh_interval'] = $json['import_wh'] - $json_first['import_wh'];
                            $json['ph1_kwh_interval'] = $json['ph1_import_kwh'] - $json_first['ph1_import_kwh'];
                            $json['ph2_kwh_interval'] = $json['ph2_import_kwh'] - $json_first['ph2_import_kwh'];
                            $json['ph3_kwh_interval'] = $json['ph3_import_kwh'] - $json_first['ph3_import_kwh'];
                            $json['varh_interval'] = $json['import_VArh'] - $json_first['import_VArh'];
                            $json['ph1_varh_interval'] = $json['ph1_import_kvarh'] - $json_first['ph1_import_kvarh'];
                            $json['ph2_varh_interval'] = $json['ph2_import_kvarh'] - $json_first['ph2_import_kvarh'];
                            $json['ph3_varh_interval'] = $json['ph3_import_kvarh'] - $json_first['ph3_import_kvarh'];
                            $json['varCh_interval'] = $json['varCh_acumm'] - $json_first['varCh_acumm'];
                            $json['ph1_varCh_interval'] = $json['ph1_varCh_acumm'] - $json_first['ph1_varCh_acumm'];
                            $json['ph2_varCh_interval'] = $json['ph2_varCh_acumm'] - $json_first['ph2_varCh_acumm'];
                            $json['ph3_varCh_interval'] = $json['ph3_varCh_acumm'] - $json_first['ph3_varCh_acumm'];
                            $json['varLh_interval'] = $json['varLh_acumm'] - $json_first['varLh_acumm'];
                            $json['ph1_varLh_interval'] = $json['ph1_varLh_acumm'] - $json_first['ph1_varLh_acumm'];
                            $json['ph2_varLh_interval'] = $json['ph2_varLh_acumm'] - $json_first['ph2_varLh_acumm'];
                            $json['ph3_varLh_interval'] = $json['ph3_varLh_acumm'] - $json_first['ph3_varLh_acumm'];

                            MonthlyMicrocontrollerData::updateOrCreate([
                                'year' => $reference_date->format('Y'),
                                'month' => $reference_date->format('m'),
                                'day' => $billing_day,
                                'client_id' => $client->id],
                                ['microcontroller_data_id' => $reference_data->microcontroller_data_id,
                                'interval_real_consumption' => $interval_active_month,
                                'interval_reactive_capacitive_consumption' => $interval_capacitive_month,
                                'interval_reactive_inductive_consumption' => $interval_inductive_month,
                                'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_month,
                                'penalizable_reactive_inductive_consumption' => $penalizable_inductive_month,
                                'raw_json' => json_encode($json),
                            ]);
                        }
                    }
                }
            }
            if ($reference_date->diffInDays($end_date_first)==0){
                break;
            }
        }
    }
    private function unpackData(){
        $data_pack = MicrocontrollerData::whereNull('client_id')
            ->whereNotNull('source_timestamp')
           ->orderBy('source_timestamp')->orderBy('created_at')
            ->get();
        echo count($data_pack)."\n";

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
    }
    private function deleteClientRelationship(){
        MicrocontrollerData::withTrashed()
            ->whereNotNull('source_timestamp')
            ->whereBetween("created_at", [$this->current_time->copy()->subDays(2)->format('Y-m-d 00:00:00'), $this->current_time->format('Y-m-d H:i:s')])
            ->restore();
        $data = MicrocontrollerData::
            whereNotNull('source_timestamp')
            ->whereBetween("created_at", [$this->current_time->copy()->subDays(2)->format('Y-m-d 00:00:00'), $this->current_time->format('Y-m-d H:i:s')])
            ->get();
        echo count($data)."\n";
        if ($data) {
            foreach ($data as $i=>&$item) {
                $item->client_id = null;
                if (is_string($item->raw_json)) {
                    $raw_json = json_decode($item->raw_json, true);
                } elseif (is_array($item->raw_json)) {
                    $raw_json = $item->raw_json;
                }
                if ($raw_json != null) {
                    $raw_json['ph1_varCh_acumm'] = $raw_json['data_ph1_varCh_acumm'];
                    $raw_json['ph2_varCh_acumm'] = $raw_json['data_ph2_varCh_acumm'];
                    $raw_json['ph3_varCh_acumm'] = $raw_json['data_ph3_varCh_acumm'];
                    $raw_json['ph1_varLh_acumm'] = $raw_json['data_ph1_varLh_acumm'];
                    $raw_json['ph2_varLh_acumm'] = $raw_json['data_ph2_varLh_acumm'];
                    $raw_json['ph3_varLh_acumm'] = $raw_json['data_ph3_varLh_acumm'];
                    $item->raw_json = json_encode($raw_json);
                    $item->saveQuietly();
                }
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
