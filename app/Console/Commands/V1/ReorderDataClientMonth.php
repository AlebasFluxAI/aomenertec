<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use App\Models\V1\DailyMicrocontrollerData;
use App\Models\V1\MonthlyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReorderDataClientMonth extends Command
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
            echo $reference_date->format('Y-m')."\n";
            foreach ($clients as $client) {
                $billing_day = $client->clientConfiguration->billing_day;
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
                    echo $client->name."  -  ".$start_date . "  -  " . $end_date . "  r: " . $reference_date->format('Y-m-d H:i:s') . "\n";
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
                            $raw_json = json_decode($item->raw_json, true);
                            foreach ($accum_variable as $index => $variable) {
                                if ($item->microcontroller_data_id != $reference_data->microcontroller_data_id) {
                                    $json[$variable['variable_name']] = $json[$variable['variable_name']] + $raw_json[$variable['variable_name']];
                                }
                            }
                            $penalizable_inductive_month = $penalizable_inductive_month + $item->penalizable_reactive_inductive_consumption;
                            $penalizable_capacitive_month = $penalizable_capacitive_month + $item->penalizable_reactive_capacitive_consumption;
                        }
                        MonthlyMicrocontrollerData::create([
                            'year' => $reference_date->format('Y'),
                            'month' => $reference_date->format('m'),
                            'day' => $billing_day,
                            'client_id' => $client->id,
                            'microcontroller_data_id' => $reference_data->microcontroller_data_id,
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
    }
}
