<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\Client;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\MonthlyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SerializeMicrocontrollerDataMonthJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $day_ref;
    public function __construct($day_ref)
    {
        $this->day_ref = new Carbon($day_ref);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $billing_day = $this->day_ref->format('d');
        $billing_day_clients = ClientConfiguration::whereBillingDay($billing_day)->get()->pluck('client_id');
        $clients_aux = Client::find($billing_day_clients);
        $clients = $clients_aux->where('has_telemetry', true)->all();
        if (count($clients)>0) {
            foreach ($clients as $client_aux) {
                $client = Client::find($client_aux->id);
                if ($this->day_ref->format('m') == '01') {
                    $month_aux = 12;
                    $year_aux = $this->day_ref->format('Y') - 1;
                } else {
                    $month_aux = $this->day_ref->format('m') - 1;
                    if ($month_aux<10) {
                        $month_aux = '0'.$month_aux;
                    }
                    $year_aux = $this->day_ref->format('Y');
                }
                $start_date = Carbon::create($year_aux, $month_aux, ($billing_day + 1));
                $end_date = Carbon::create($this->day_ref->format('Y'), $this->day_ref->format('m'), $billing_day, "23", "59", 59);

                $data_aux = $client->dailyMicrocontrollerData()
                    ->where('year', $year_aux)
                    ->where('month', ($month_aux))
                    ->whereBetween('day', ['0' . ($billing_day + 1), ($start_date->format('t'))]);
                $data_month = $client->dailyMicrocontrollerData()
                    ->where('year', $this->day_ref->format('Y'))
                    ->where('month', $this->day_ref->format('m'))
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
                            'year' => $this->day_ref->format('Y'),
                            'month' => $this->day_ref->format('m'),
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
    }
}
