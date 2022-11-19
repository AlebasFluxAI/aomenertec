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
                            ->whereDate('source_timestamp', $reference_date->copy()->subDay()->format('Y-m-d'))
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
                                $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                                foreach ($accum_variable as $index => $variable) {
                                    if ($item->microcontroller_data_id != $reference_data->id) {
                                        $json[$variable['variable_name']] = $json[$variable['variable_name']] + $raw_json[$variable['variable_name']];
                                    }
                                }
                                $penalizable_inductive_day = $penalizable_inductive_day + $item->penalizable_reactive_inductive_consumption;
                                $penalizable_capacitive_day = $penalizable_capacitive_day + $item->penalizable_reactive_capacitive_consumption;
                            }
                        }
                        DailyMicrocontrollerData::create([
                            'year' => $reference_date->format('Y'),
                            'month' => $reference_date->format('m'),
                            'day' => $reference_date->format('d'),
                            'client_id' => $client->id,
                            'microcontroller_data_id' => $reference_data->id,
                            'interval_real_consumption' => $interval_active_day,
                            'interval_reactive_capacitive_consumption' => $interval_capacitive_day,
                            'interval_reactive_inductive_consumption' => $interval_inductive_day,
                            'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_day,
                            'penalizable_reactive_inductive_consumption' => $penalizable_inductive_day,
                            'raw_json' => json_encode($json),
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
