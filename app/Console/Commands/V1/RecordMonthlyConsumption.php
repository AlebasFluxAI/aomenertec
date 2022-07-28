<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\MonthlyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecordMonthlyConsumption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:record_monthly_consumption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run every day at 00:09 am recording monthly consumption to clients';

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
        $data_frame = collect(config('data-frame.data_frame'));
        $accum_variable = $data_frame->where('bolean_accum', true);
        $null_data = MonthlyMicrocontrollerData::whereNull('microcontroller_data_id')->get();
        foreach ($null_data as $data){
            $billing_day = $data->client->clientConfiguration->billing_day;
            if ($data->month == 1){
                $month_aux = 12;
                $year_aux = $data->year - 1;
            } else {
                $month_aux = $data->month - 1;
                $year_aux = $data->year;
            }
            $aux_date = Carbon::create($year_aux, $month_aux, $billing_day + 1);
            $data_aux = $data->client->dailyMicrocontrollerData()
                ->where('year', $year_aux)
                ->where('month', ($month_aux))
                ->whereBetween('day', [($billing_day + 1), $aux_date->format('t')] );
            $data_month = $data->client->dailyMicrocontrollerData()
                ->where('year', $data->year)
                ->where('month', $data->month)
                ->whereBetween('day', [1, $billing_day])
                ->union($data_aux)
                ->get();
            $start_date = Carbon::create($year_aux, $month_aux, ($billing_day + 1));
            $end_date = Carbon::create($data->year, $data->month, $billing_day);
            if (count($data_month) > 0) {
                $end_data = $data->client->microcontrollerData()
                    ->whereBetween('source_timestamp', [$start_date->format('Y-m-d 00:00:00'),$end_date->format('Y-m-d 23:59:59')])
                    ->orderBy('source_timestamp', 'desc')
                    ->first();
                $reference_data = $end_data->dailyMicrocontrollerData;
                $json = json_decode($reference_data->raw_json, true);
                $penalizable_inductive_month = 0;
                $penalizable_capacitive_month = 0;
                $interval_active_month = 0;
                $interval_capacitive_month = 0;
                $interval_inductive_month = 0;
                foreach ($data_month as $item) {
                    $raw_json = json_decode($item->raw_json, true);
                    foreach ($accum_variable as $index=>$variable) {
                        if ($item->microcontroller_data_id != $reference_data->microcontroller_data_id) {
                            $json[$variable['variable_name']] = $json[$variable['variable_name']] + $raw_json[$variable['variable_name']];
                        }
                    }
                    $interval_active_month = $interval_active_month + $item->interval_real_consumption;
                    $interval_capacitive_month = $interval_capacitive_month + $item->interval_reactive_capacitive_consumption;
                    $interval_inductive_month = $interval_inductive_month + $item->interval_reactive_inductive_consumption;
                    $penalizable_inductive_month = $penalizable_inductive_month + $item->penalizable_reactive_inductive_consumption;
                    $penalizable_capacitive_month = $penalizable_capacitive_month + $item->penalizable_reactive_capacitive_consumption;
                }
                $data->microcontroller_data_id = $reference_data->microcontroller_data_id;
                $data->interval_real_consumption = $interval_active_month;
                $data->interval_reactive_capacitive_consumption = $interval_capacitive_month;
                $data->interval_reactive_inductive_consumption = $interval_inductive_month;
                $data->penalizable_reactive_capacitive_consumption = $penalizable_capacitive_month;
                $data->penalizable_reactive_inductive_consumption = $penalizable_inductive_month;
                $data->raw_json = json_encode($json);
                $data->save();
            }
        }
        $reference_date = new Carbon();
        $aux_date = new Carbon();
        $aux_date->subMonths(3);
        $null_data = MonthlyMicrocontrollerData::whereNull('microcontroller_data_id')
            ->whereYear('created_at', '<', $aux_date->format('Y'))
            ->whereMonth('created_at', '<', $aux_date->format('m'))
            ->get();
        foreach ($null_data as $data){
            $data->delete();
        }

        $reference_date->subDay();
        $billing_day = $reference_date->format('d');
        $billing_day_clients = ClientConfiguration::whereBillingDay($billing_day)->get()->pluck('client_id');
        $clients = Client::find($billing_day_clients);
        foreach ($clients as $client) {
            if ($reference_date->format('m') == 1){
                $month_aux = 12;
                $year_aux = $reference_date->format('y') - 1;
            } else {
                $month_aux = $reference_date->format('m') - 1;
                $year_aux = $reference_date->format('y');
            }
            $start_date = Carbon::create($year_aux, $month_aux, ($billing_day + 1));
            $end_date = Carbon::create($reference_date->format('y'), $reference_date->format('m'), $billing_day);
            $data_aux = $data->client->dailyMicrocontrollerData()
                ->where('year', $year_aux)
                ->where('month', ($month_aux))
                ->whereBetween('day', [($billing_day + 1), $start_date->format('t')] );
            $data_month = $data->client->dailyMicrocontrollerData()
                ->where('year', $reference_date->format('y'))
                ->where('month', $reference_date->format('m'))
                ->whereBetween('day', [1, $billing_day])
                ->union($data_aux)
                ->get();

            if (count($data_month) > 0) {
                $end_data = $data->client->microcontrollerData()
                    ->whereBetween('source_timestamp', [$start_date->format('Y-m-d 00:00:00'),$end_date->format('Y-m-d 23:59:59')])
                    ->orderBy('source_timestamp', 'desc')
                    ->first();
                $reference_data = $end_data->dailyMicrocontrollerData;
                $json = json_decode($reference_data->raw_json, true);
                $penalizable_inductive_month = 0;
                $penalizable_capacitive_month = 0;
                $interval_active_month = 0;
                $interval_capacitive_month = 0;
                $interval_inductive_month = 0;
                foreach ($data_month as $item) {
                    $raw_json = json_decode($item->raw_json, true);
                    foreach ($accum_variable as $index => $variable) {
                        if ($item->microcontroller_data_id != $reference_data->microcontroller_data_id) {
                            $json[$variable['variable_name']] = $json[$variable['variable_name']] + $raw_json[$variable['variable_name']];
                        }
                    }
                    $interval_active_month = $interval_active_month + $item->interval_real_consumption;
                    $interval_capacitive_month = $interval_capacitive_month + $item->interval_reactive_capacitive_consumption;
                    $interval_inductive_month = $interval_inductive_month + $item->interval_reactive_inductive_consumption;
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

            } else {
                MonthlyMicrocontrollerData::create([
                    'year' => $reference_date->format('Y'),
                    'month' => $reference_date->format('m'),
                    'day' => $billing_day,
                    'client_id' => $client->id
                ]);
            }
        }
    }
}
