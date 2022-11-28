<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use App\Models\V1\DailyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReorderDataClientDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:reorder_daily_data_client';

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
        $clients = Client::whereHasTelemetry(true)->get();
        //$clients = Client::find([66,67]);
        $data_frame = collect(config('data-frame.data_frame'));
        $accum_variable = $data_frame->where('bolean_accum', true);
        $reference_date = new Carbon();
        $end_date= Carbon::create(2022,07,16);
        while (true) {
            $reference_date->subDay();
            echo $reference_date->format('Y-m-d')."\n";
            foreach ($clients as $client) {
                $data_day = $client->hourlyMicrocontrollerData()
                    ->where('year', $reference_date->format('Y'))
                    ->where('month', $reference_date->format('m'))
                    ->where('day', $reference_date->format('d'))->get();

                if (count($data_day) > 0) {
                    $reference_data = $client->microcontrollerData()
                        ->whereDate('source_timestamp', $reference_date->format('Y-m-d'))
                        ->orderBy('source_timestamp', 'desc')
                        ->first();

                    if ($client->microcontrollerData()
                        ->whereDate('source_timestamp', $reference_date->copy()->subDay()->format('Y-m-d'))->exists()){
                        $reference_data_first = $client->microcontrollerData()
                            ->whereBetween('source_timestamp', [$reference_date->copy()->subDay()->format('Y-m-d 00:00:00'), $reference_date->copy()->subDay()->format('Y-m-d 23:59:59')])
                            ->orderBy('source_timestamp', 'desc')
                            ->first();
                    } else{
                        $reference_data_first = $client->microcontrollerData()
                            ->whereDate('source_timestamp', $reference_date->format('Y-m-d'))
                            ->orderBy('source_timestamp')
                            ->first();
                    }
                    echo $client->id."\n";
                    if ($reference_data) {
                        $json = json_decode($reference_data->raw_json, true);
                        $penalizable_inductive_day = 0;
                        $penalizable_capacitive_day = 0;
                        $interval_active_day = $reference_data->accumulated_real_consumption - $reference_data_first->accumulated_real_consumption;
                        $interval_capacitive_day = $reference_data->accumulated_reactive_capacitive_consumption - $reference_data_first->accumulated_reactive_capacitive_consumption;
                        $interval_inductive_day = $reference_data->accumulated_reactive_inductive_consumption - $reference_data_first->accumulated_reactive_inductive_consumption;
                        foreach ($data_day as $item) {
                            if ($item->microcontrollerData) {

                                $penalizable_inductive_day = $penalizable_inductive_day + $item->penalizable_reactive_inductive_consumption;
                                $penalizable_capacitive_day = $penalizable_capacitive_day + $item->penalizable_reactive_capacitive_consumption;
                            }
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
                            'year' => $reference_date->format('Y'),
                            'month' => $reference_date->format('m'),
                            'day' => $reference_date->format('d'),
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
                }
            }
            if ($reference_date->diffInDays($end_date)==0){
                break;
            }
        }
    }
}
