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
        foreach ($null_data as $data) {
            $billing_day = $data->client->clientConfiguration->billing_day;
            if ($data->month == '01') {
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
                ->whereBetween('day', [($billing_day + 1), $aux_date->format('t')]);
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
                    ->whereBetween('source_timestamp', [$start_date->format('Y-m-d 00:00:00'), $end_date->format('Y-m-d 23:59:59')])
                    ->orderBy('source_timestamp', 'desc')
                    ->first();
                $start_data = $data->client->microcontrollerData()
                    ->whereBetween('source_timestamp', [$start_date->format('Y-m-d 00:00:00'), $end_date->format('Y-m-d 23:59:59')])
                    ->orderBy('source_timestamp')
                    ->first();
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
        foreach ($null_data as $data) {
            $data->delete();
        }
        $reference_date->subDay();
        $billing_day = $reference_date->format('d');
        $billing_day_clients = ClientConfiguration::whereBillingDay($billing_day)->get()->pluck('client_id');
        $clients_aux = Client::find($billing_day_clients);
        $clients = $clients_aux->where('has_telemetry', true)->all();
        if (count($clients) > 0) {
            foreach ($clients as $client_aux) {
                $client = Client::find($client_aux->id);
                if ($reference_date->format('m') == '01') {
                    $month_aux = 12;
                    $year_aux = $reference_date->format('Y') - 1;
                } else {
                    $month_aux = $reference_date->format('m') - 1;
                    if ($month_aux < 10) {
                        $month_aux = '0' . $month_aux;
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
}
