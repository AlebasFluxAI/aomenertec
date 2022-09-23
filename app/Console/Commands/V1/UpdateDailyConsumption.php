<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateDailyConsumption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:update_daily_consumption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run every day at 00:06 am updating monthly consumption to clients';

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
        $fortnight_date = new Carbon();
        $reference_date = new Carbon();
        $fortnight_date->subDays(200);
        $reference_date->subDay();
        $clients = Client::whereHasTelemetry(true)->get();
        foreach ($clients as $client) {
            //$billing_day = $client->clientConfiguration->billing_day;
            $data_frame = collect(config('data-frame.data_frame'));
            $accum_variable = $data_frame->where('bolean_accum', true);
            $data_aux = $client->dailyMicrocontrollerData()
                ->where('year', $fortnight_date->format('y'))
                ->where('month', $fortnight_date->format('m'))
                ->whereBetween('day', [$fortnight_date->format('d'), $fortnight_date->format('t')]);
            $fortnight_data = $client->dailyMicrocontrollerData()
                ->where('year', $reference_date->format('y'))
                ->where('month', $reference_date->format('m'))
                ->whereBetween('day', [1, $reference_date->format('d')])
                ->union($data_aux)
                ->get();
            if (count($fortnight_data) > 0) {
                foreach ($fortnight_data as $daily_data) {
                    $date = Carbon::create($daily_data->year, $daily_data->month, $daily_data->day);
                    $data = $client->microcontrollerData()
                        ->whereDate('source_timestamp', $date->format('Y-m-d'))
                        ->orderBy('source_timestamp', 'desc')
                        ->first();
                    if ($daily_data->microcontoller_data_id != $data->id) {
                        $data_day = $client->hourlyMicrocontrollerData()
                            ->where('year', $daily_data->year)
                            ->where('month', $daily_data->month)
                            ->where('day', $daily_data->day)->get();
                        if (count($data_day) > 0) {
                            $reference_data_first = $client->microcontrollerData()
                                ->whereDate('source_timestamp', $reference_date->format('Y-m-d'))
                                ->orderBy('source_timestamp')
                                ->first();
                            $json = json_decode($data->raw_json, true);
                            $penalizable_inductive_day = 0;
                            $penalizable_capacitive_day = 0;
                            $interval_active_day = $data->accumulated_real_consumption - $reference_data_first->accumulated_real_consumption;
                            $interval_capacitive_day = $data->accumulated_reactive_capacitive_consumption - $reference_data_first->accumulated_reactive_capacitive_consumption;
                            $interval_inductive_day = $data->accumulated_reactive_inductive_consumption - $reference_data_first->accumulated_reactive_inductive_consumption;

                            foreach ($data_day as $item) {
                                $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                                foreach ($accum_variable as $index => $variable) {
                                    if ($item->microcontroller_data_id != $data->id) {
                                        $json[$variable['variable_name']] = $json[$variable['variable_name']] + $raw_json[$variable['variable_name']];
                                    }
                                }
                                $penalizable_inductive_day = $penalizable_inductive_day + $item->penalizable_reactive_inductive_consumption;
                                $penalizable_capacitive_day = $penalizable_capacitive_day + $item->penalizable_reactive_capacitive_consumption;
                            }
                            $daily_data->microcontroller_data_id = $data->id;
                            $daily_data->interval_real_consumption = $interval_active_day;
                            $daily_data->interval_reactive_capacitive_consumption = $interval_capacitive_day;
                            $daily_data->interval_reactive_inductive_consumption = $interval_inductive_day;
                            $daily_data->penalizable_reactive_capacitive_consumption = $penalizable_capacitive_day;
                            $daily_data->penalizable_reactive_inductive_consumption = $penalizable_inductive_day;
                            $daily_data->raw_json = json_encode($json);
                            $daily_data->save();
                        }
                    }
                }
            }
        }
    }
}
