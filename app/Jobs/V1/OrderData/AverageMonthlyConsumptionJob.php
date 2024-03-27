<?php

namespace App\Jobs\V1\OrderData;

use App\Models\V1\MonthlyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AverageMonthlyConsumptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $client;
    public $day_reference;

    public function __construct($client_id, Carbon $day_reference)
    {
        $this->client = \App\Models\V1\Client::find($client_id);
        $this->day_reference = $day_reference;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $billing_day = $this->day_reference->format('d');
        $previous_month_date = $this->day_reference->copy()->subMonthNoOverflow();
        $year_aux = $previous_month_date->format('Y');
        $month_aux = $previous_month_date->format('m');

        if ($billing_day == $this->day_reference->format('t')) {
            $start_date = Carbon::create($year_aux, $month_aux, $previous_month_date->format('t'), 23, 59, 59);
            $end_date = Carbon::create($this->day_reference->format('Y'), $this->day_reference->format('m'), $this->day_reference->format('t'), 23, 59, 59);
            $data_month = $this->client->dailyMicrocontrollerData()
                ->where('year', $this->day_reference->format('Y'))
                ->where('month', $this->day_reference->format('m'))
                ->whereBetween('day', ['01', $billing_day])
                ->get();
        } else {
            $start_date = Carbon::create($year_aux, $month_aux, ($billing_day), 23, 59, 59);
            $end_date = Carbon::create($this->day_reference->format('Y'), $this->day_reference->format('m'), $billing_day, "23", "59", 59);
            $data_aux = $this->client->dailyMicrocontrollerData()
                ->where('year', $year_aux)
                ->where('month', ($month_aux))
                ->whereBetween('day', [str_pad((strval(($billing_day + 1))), 2, "0", STR_PAD_LEFT), ($start_date->format('t'))]);
            $data_month = $this->client->dailyMicrocontrollerData()
                ->where('year', $this->day_reference->format('Y'))
                ->where('month', $this->day_reference->format('m'))
                ->whereBetween('day', ['01', $billing_day])
                ->union($data_aux)
                ->get();
        }

        if (count($data_month) > 0) {
            $reference_data = $this->client->microcontrollerData()
                ->whereBetween('source_timestamp', [$start_date->format('Y-m-d H:i:s'), $end_date->format('Y-m-d 23:59:59')])
                ->orderBy('source_timestamp', 'desc')
                ->first();
            if ($reference_data) {
                $date_reference_data = new Carbon($reference_data->source_timestamp);
                if ($this->day_reference->diffInDays($date_reference_data) <= 4) {
                    $start_data_aux = $this->client->monthlyMicrocontrollerData()
                        ->where('year', $previous_month_date->format('Y'))
                        ->where('month', $previous_month_date->format('m'))->first();
                    if (empty($start_data_aux)) {
                        $start_data = $this->client->microcontrollerData()
                            ->whereDate('source_timestamp', $start_date->format('Y-m-d 00:00:00'))
                            ->orderBy('source_timestamp', 'desc')
                            ->first();
                        if (empty($start_data)) {
                            $start_data = $this->client->microcontrollerData()
                                ->whereBetween('source_timestamp', [$start_date->format('Y-m-d 00:00:00'), $start_date->format('Y-m-d 23:59:59')])
                                ->orderBy('source_timestamp', 'desc')
                                ->first();
                            if (empty($start_data)) {
                                $start_data = $this->client->microcontrollerData()
                                    ->whereBetween('source_timestamp', [$start_date->copy()->subDays(15)->format('Y-m-d 00:00:00'), $start_date->format('Y-m-d 23:59:59')])
                                    ->orderBy('source_timestamp')
                                    ->first();
                            }
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
                    if ($start_data) {
                        $json = json_decode($reference_data->raw_json, true);
                        $penalizable_inductive_month = 0;
                        $penalizable_capacitive_month = 0;
                        $interval_active_month = $reference_data->accumulated_real_consumption - $start_data->accumulated_real_consumption;
                        $interval_capacitive_month = $reference_data->accumulated_reactive_capacitive_consumption - $start_data->accumulated_reactive_capacitive_consumption;
                        $interval_inductive_month = $reference_data->accumulated_reactive_inductive_consumption - $start_data->accumulated_reactive_inductive_consumption;
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
                            'year' => $this->day_reference->format('Y'),
                            'month' => $this->day_reference->format('m'),
                            'day' => $date_reference_data->format('d'),
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
            } else {
                $last_month_data = $this->client->dailyMicrocontrollerData()
                    ->where('year', $previous_month_date->format('Y'))
                    ->where('month', $previous_month_date->format('m'))
                    ->orderBy('year', 'desc')->orderBy('month', 'desc')->first();
                if ($last_month_data == null) {
                    $last_month_data = $this->client->dailyMicrocontrollerData()
                        ->whereHas('microcontrollerData', function ($query) use ($previous_month_date) {
                            $query->whereBetween("source_timestamp", [$previous_month_date->copy()->subMonthNoOverflow()->format('Y-m-01 00:00:00'), $previous_month_date->format('Y-m-01 00:00:00')]);
                        })
                        ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                        ->first();
                }
                if ($last_month_data) {
                    $data_frame = config('data-frame.data_frame');
                    $raw_json = json_decode($last_month_data->raw_json, true);
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
                    MonthlyMicrocontrollerData::updateOrCreate(
                        ['year' => $this->day_reference->format('Y'),
                            'month' => $this->day_reference->format('m'),
                            'day' => $billing_day,
                            'client_id' => $this->client->id],
                        ['microcontroller_data_id' => $last_month_data->microcontroller_data_id,
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
            $month_data = $this->client->monthlyMicrocontrollerdata()
                ->where('year', $this->day_reference->format('Y'))
                ->where('month', $this->day_reference->format('m'))->first();
            if ($month_data) {
                if ($month_data->interval_real_consumption != 0) {
                    $last_raw_json = json_decode($month_data->raw_json, true);
                    $previous_month_data = $this->client->monthlyMicrocontrollerdata()
                        ->where('year', $year_aux)
                        ->where('month', $month_aux)
                        ->first();
                    if ($previous_month_data == null) {
                        $previous_month_data = $this->client->monthlyMicrocontrollerData()
                            ->where('year', $previous_month_date->copy()->subMonthsNoOverflow(6)->format('y'))
                            ->where('month', $previous_month_date->copy()->subMonthsNoOverflow(6)->format('m'))
                            ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                            ->first();
                    }
                    if ($previous_month_data) {
                        if ($previous_month_data->microcontroller_data_id != $month_data->microcontroller_data_id) {
                            $data = MonthlyMicrocontrollerData::whereMicrocontrollerDataId($previous_month_data->microcontroller_data_id)->orderBy('year')->orderBy('month')->orderBy('day')->get();
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
                                        $average_accumulated_reactive_inductive_consumption = ($last_raw_json['varLh_acumm'] - $first_raw_json['varLh_acumm']) / count($data);
                                        $average_accumulated_reactive_inductive_consumption_ph1 = ($last_raw_json['ph1_varLh_acumm'] - $first_raw_json['ph1_varLh_acumm']) / count($data);
                                        $average_accumulated_reactive_inductive_consumption_ph2 = ($last_raw_json['ph2_varLh_acumm'] - $first_raw_json['ph2_varLh_acumm']) / count($data);
                                        $average_accumulated_reactive_inductive_consumption_ph3 = ($last_raw_json['ph3_varLh_acumm'] - $first_raw_json['ph3_varLh_acumm']) / count($data);
                                        $average_accumulated_reactive_capacitive_consumption = ($last_raw_json['varCh_acumm'] - $first_raw_json['varCh_acumm']) / count($data);
                                        $average_accumulated_reactive_capacitive_consumption_ph1 = ($last_raw_json['ph1_varCh_acumm'] - $first_raw_json['ph1_varCh_acumm']) / count($data);
                                        $average_accumulated_reactive_capacitive_consumption_ph2 = ($last_raw_json['ph2_varCh_acumm'] - $first_raw_json['ph2_varCh_acumm']) / count($data);
                                        $average_accumulated_reactive_capacitive_consumption_ph3 = ($last_raw_json['ph3_varCh_acumm'] - $first_raw_json['ph3_varCh_acumm']) / count($data);

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
                                        $raw_json['varLh_acumm'] = $first_raw_json['varLh_acumm'] + ($average_accumulated_reactive_inductive_consumption * $i);
                                        $raw_json['varLh_interval'] = $average_accumulated_reactive_inductive_consumption;
                                        $raw_json['ph1_varLh_acumm'] = $first_raw_json['ph1_varLh_acumm'] + ($average_accumulated_reactive_inductive_consumption_ph1 * $i);
                                        $raw_json['ph2_varLh_acumm'] = $first_raw_json['ph2_varLh_acumm'] + ($average_accumulated_reactive_inductive_consumption_ph2 * $i);
                                        $raw_json['ph3_varLh_acumm'] = $first_raw_json['ph3_varLh_acumm'] + ($average_accumulated_reactive_inductive_consumption_ph3 * $i);
                                        $raw_json['ph1_varLh_interval'] = $average_accumulated_reactive_inductive_consumption_ph1;
                                        $raw_json['ph2_varLh_interval'] = $average_accumulated_reactive_inductive_consumption_ph2;
                                        $raw_json['ph3_varLh_interval'] = $average_accumulated_reactive_inductive_consumption_ph3;
                                        $raw_json['varCh_acumm'] = $first_raw_json['varCh_acumm'] + ($average_accumulated_reactive_capacitive_consumption * $i);
                                        $raw_json['varCh_interval'] = $average_accumulated_reactive_capacitive_consumption;
                                        $raw_json['ph1_varCh_acumm'] = $first_raw_json['ph1_varCh_acumm'] + ($average_accumulated_reactive_capacitive_consumption_ph1 * $i);
                                        $raw_json['ph2_varCh_acumm'] = $first_raw_json['ph2_varCh_acumm'] + ($average_accumulated_reactive_capacitive_consumption_ph2 * $i);
                                        $raw_json['ph3_varCh_acumm'] = $first_raw_json['ph3_varCh_acumm'] + ($average_accumulated_reactive_capacitive_consumption_ph3 * $i);
                                        $raw_json['ph1_varCh_interval'] = $average_accumulated_reactive_capacitive_consumption_ph1;
                                        $raw_json['ph2_varCh_interval'] = $average_accumulated_reactive_capacitive_consumption_ph2;
                                        $raw_json['ph3_varCh_interval'] = $average_accumulated_reactive_capacitive_consumption_ph3;

                                        $datum->raw_json = json_encode($raw_json);
                                        $datum->accumulated_real_consumption = $raw_json['import_wh'];
                                        $datum->interval_real_consumption = $raw_json['kwh_interval'];
                                        $datum->accumulated_reactive_consumption = $raw_json['import_VArh'];
                                        $datum->interval_reactive_consumption = $raw_json['varh_interval'];
                                        $datum->accumulated_reactive_capacitive_consumption = $raw_json['varCh_acumm'];
                                        $datum->accumulated_reactive_inductive_consumption = $raw_json['varLh_acumm'];
                                        $datum->interval_reactive_capacitive_consumption = $raw_json['varCh_interval'];
                                        $datum->interval_reactive_inductive_consumption = $raw_json['varLh_interval'];
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
                                $last_raw_json['varLh_interval'] = $average_accumulated_reactive_inductive_consumption;
                                $last_raw_json['ph1_varLh_interval'] = $average_accumulated_reactive_inductive_consumption_ph1;
                                $last_raw_json['ph2_varLh_interval'] = $average_accumulated_reactive_inductive_consumption_ph2;
                                $last_raw_json['ph3_varLh_interval'] = $average_accumulated_reactive_inductive_consumption_ph3;
                                $last_raw_json['varCh_interval'] = $average_accumulated_reactive_capacitive_consumption;
                                $last_raw_json['ph1_varCh_interval'] = $average_accumulated_reactive_capacitive_consumption_ph1;
                                $last_raw_json['ph2_varCh_interval'] = $average_accumulated_reactive_capacitive_consumption_ph2;
                                $last_raw_json['ph3_varCh_interval'] = $average_accumulated_reactive_capacitive_consumption_ph3;
                                $month_data->raw_json = json_encode($raw_json);
                                $month_data->accumulated_real_consumption = $raw_json['import_wh'];
                                $month_data->interval_real_consumption = $raw_json['kwh_interval'];
                                $month_data->accumulated_reactive_consumption = $raw_json['import_VArh'];
                                $month_data->interval_reactive_consumption = $raw_json['varh_interval'];
                                $month_data->accumulated_reactive_capacitive_consumption = $raw_json['varCh_acumm'];
                                $month_data->accumulated_reactive_inductive_consumption = $raw_json['varLh_acumm'];
                                $month_data->interval_reactive_capacitive_consumption = $raw_json['varCh_interval'];
                                $month_data->interval_reactive_inductive_consumption = $raw_json['varLh_interval'];
                                $month_data->save();
                            }
                        }

                    }
                }
            }

        }
    }
}
