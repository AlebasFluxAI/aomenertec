<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use App\Models\V1\ClientConfiguration;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateMonthlyConsumption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:update_monthly_consumption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run every day at 00:12 am updating monthly consumption to clients';

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
        $month_date = new Carbon();
        $reference_date = new Carbon();
        $month_date->subDays(16);
        $reference_date->subDay();
        $billing_day = $reference_date->format('d');
        $billing_day_clients = ClientConfiguration::whereBillingDay($billing_day)->get()->pluck('client_id');
        $clients = Client::find($billing_day_clients)->where('has_telemetry', true)->get();
        foreach ($clients as $client) {
            $billing_day = $client->clientConfiguration->billing_day;
            $data_frame = collect(config('data-frame.data_frame'));
            $accum_variable = $data_frame->where('bolean_accum', true);
            $month_data = $client->monthlyMicrocontrollerData()
                ->where('year', $reference_date->format('Y'))
                ->whereBetween('month', [$month_date->format('m'), $reference_date->format('m')])
                ->get();
            if (count($month_data) > 0) {
                foreach ($month_data as $monthly_data) {
                    if ($monthly_data->month == 1) {
                        $month_aux = 12;
                        $year_aux = $monthly_data->year - 1;
                    } else {
                        $month_aux = $monthly_data->month - 1;
                        if ($month_aux < 10) {
                            $month_aux = '0' . $month_aux;
                        }
                        $year_aux = $monthly_data->year;
                    }
                    $start_date = Carbon::create($year_aux, $month_aux, ($billing_day + 1));
                    $end_date = Carbon::create($monthly_data->year, $monthly_data->month, $billing_day);
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
                        if ($monthly_data->microcontoller_data_id != $end_data->id) {
                            $data_aux = $client->dailyMicrocontrollerData()
                                ->where('year', $year_aux)
                                ->where('month', ($month_aux))
                                ->whereBetween('day', [($billing_day + 1), $start_date->format('t')]);
                            $data_month = $client->dailyMicrocontrollerData()
                                ->where('year', $monthly_data->year)
                                ->where('month', $monthly_data->month)
                                ->whereBetween('day', [1, $billing_day])
                                ->union($data_aux)
                                ->get();
                            if (count($data_month) > 0) {
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
                                $monthly_data->microcontroller_data_id = $reference_data->microcontroller_data_id;
                                $monthly_data->interval_real_consumption = $interval_active_month;
                                $monthly_data->interval_reactive_capacitive_consumption = $interval_capacitive_month;
                                $monthly_data->interval_reactive_inductive_consumption = $interval_inductive_month;
                                $monthly_data->penalizable_reactive_capacitive_consumption = $penalizable_capacitive_month;
                                $monthly_data->penalizable_reactive_inductive_consumption = $penalizable_inductive_month;
                                $monthly_data->raw_json = json_encode($json);
                                $monthly_data->save();
                            }
                        }
                    }
                }
            }
        }
    }
}
