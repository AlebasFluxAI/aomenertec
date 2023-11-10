<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\Client;
use App\Models\V1\MonthlyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
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
    public $client;

    public function __construct($day_ref, $client_id)
    {
        $this->day_ref = new Carbon($day_ref);
        $this->client = Client::find($client_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $billing_day = $this->day_ref->format('d');
        if ($this->day_ref->format('m') == '01') {
            $month_aux = 12;
            $year_aux = $this->day_ref->format('Y') - 1;
        } else {
            $month_aux = $this->day_ref->format('m') - 1;
            if ($month_aux < 10) {
                $month_aux = '0' . $month_aux;
            }
            $year_aux = $this->day_ref->format('Y');
        }
        if ($billing_day == $this->day_ref->format('t')) {
            $date_aux = Carbon::create($year_aux, $month_aux, 2);
            $start_date = Carbon::create($year_aux, $month_aux, $date_aux->format('t'), 23, 59, 59);
            $end_date = Carbon::create($this->day_ref->format('Y'), $this->day_ref->format('m'), $this->day_ref->format('t'), 23, 59, 59);
        } else {
            $start_date = Carbon::create($year_aux, $month_aux, ($billing_day), 23, 59, 59);
            $end_date = Carbon::create($this->day_ref->format('Y'), $this->day_ref->format('m'), $billing_day, "23", "59", 59);
        }
        if ($billing_day == $this->day_ref->format('t')) {
            $data_month = $this->client->dailyMicrocontrollerData()
                ->where('year', $this->day_ref->format('Y'))
                ->where('month', $this->day_ref->format('m'))
                ->whereBetween('day', ['01', $billing_day])
                ->get();
        } else {
            $data_aux = $this->client->dailyMicrocontrollerData()
                ->where('year', $year_aux)
                ->where('month', ($month_aux))
                ->whereBetween('day', [str_pad((strval(($billing_day + 1))), 2, "0", STR_PAD_LEFT), ($start_date->format('t'))]);
            $data_month = $this->client->dailyMicrocontrollerData()
                ->where('year', $this->day_ref->format('Y'))
                ->where('month', $this->day_ref->format('m'))
                ->whereBetween('day', ['01', $billing_day])
                ->union($data_aux)
                ->get();
        }
        if (count($data_month) > 0) {
            $end_data = $this->client->microcontrollerData()
                ->whereBetween('source_timestamp', [$start_date->format('Y-m-d H:i:s'), $end_date->format('Y-m-d 23:59:59')])
                ->orderBy('source_timestamp', 'desc')
                ->first();
            $date_end_data = new Carbon($end_data->source_timestamp);
            if ($date_end_data->isSameDay($this->day_ref)) {

                $start_data_aux = $this->client->monthlyMicrocontrollerData()
                    ->where('year', $start_date->format('Y'))
                    ->where('month', $start_date->format('m'))->first();
                if (empty($start_data_aux)) {
                    $start_data = $this->client->microcontrollerData()
                        ->whereDate('source_timestamp', $start_date->format('Y-m-d 00:00:00'))
                        ->orderBy('source_timestamp', 'desc')
                        ->first();
                    if (empty($start_data)) {
                        $start_data = $this->client->microcontrollerData()
                            ->whereDate('source_timestamp', '<', $start_date->format('Y-m-d 00:00:00'))
                            ->orderBy('source_timestamp', 'desc')
                            ->first();
                        if (empty($start_data)) {
                            $start_data = $this->client->microcontrollerData()
                                ->whereBetween('source_timestamp', [$start_date->format('Y-m-d H:i:s'), $end_date->format('Y-m-d 23:59:59')])
                                ->orderBy('source_timestamp')
                                ->first();
                        }
                    }
                } else {
                    $start_data = $start_data_aux->microcontrollerData;
                }
                if ($end_data) {
                    $reference_data = $end_data;
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
                        'client_id' => $this->client->id],
                        ['microcontroller_data_id' => $reference_data->id,
                            'interval_real_consumption' => $interval_active_month,
                            'interval_reactive_capacitive_consumption' => $interval_capacitive_month,
                            'interval_reactive_inductive_consumption' => $interval_inductive_month,
                            'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_month,
                            'penalizable_reactive_inductive_consumption' => $penalizable_inductive_month,
                            'raw_json' => json_encode($json),
                        ]);
                }
            } else {
                // generar orden de trabajo de lectura para este cliente
            }
        }
    }
}
