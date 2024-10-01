<?php

namespace App\Jobs\V1\OrderData;

use App\Models\V1\DailyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AverageDailyConsumptionJob implements ShouldQueue
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
        $reference_data = $this->client->microcontrollerData()
            ->whereBetween('source_timestamp', [$this->day_reference->format('Y-m-d 00:00:00'), $this->day_reference->format('Y-m-d 23:59:59')])
            ->orderBy('source_timestamp', 'desc')
            ->first();
        if ($reference_data) {
            $data_day = $this->client->hourlyMicrocontrollerData()
                ->where('year', $this->day_reference->format('Y'))
                ->where('month', $this->day_reference->format('m'))
                ->where('day', $this->day_reference->format('d'))->get();
            $reference_data_first = $this->client->microcontrollerData()
                ->whereBetween('source_timestamp', [$this->day_reference->copy()->subDay()->format('Y-m-d 00:00:00'), $this->day_reference->copy()->subDay()->format('Y-m-d 23:59:59')])
                ->orderBy('source_timestamp', 'desc')
                ->first();
            if ($reference_data_first == null) {
                $reference_data_first = $this->client->microcontrollerData()
                    ->whereBetween('source_timestamp', [$this->day_reference->copy()->subDays(15)->format('Y-m-d 00:00:00'), $this->day_reference->copy()->subDay()->format('Y-m-d 23:59:59')])
                    ->orderBy('source_timestamp', 'desc')
                    ->first();
                if ($reference_data_first == null) {
                    $reference_data_first = $this->client->microcontrollerData()
                        ->whereBetween('source_timestamp', [$this->day_reference->format('Y-m-d 00:00:00'), $this->day_reference->format('Y-m-d 23:59:59')])
                        ->orderBy('source_timestamp')
                        ->first();
                }
            }
            if ($reference_data_first) {
                $json = json_decode($reference_data->raw_json, true);
                $penalizable_inductive_day = 0;
                $penalizable_capacitive_day = 0;
                $interval_active_day = $reference_data->accumulated_real_consumption - $reference_data_first->accumulated_real_consumption;
                $interval_capacitive_day = $reference_data->accumulated_reactive_capacitive_consumption - $reference_data_first->accumulated_reactive_capacitive_consumption;
                $interval_inductive_day = $reference_data->accumulated_reactive_inductive_consumption - $reference_data_first->accumulated_reactive_inductive_consumption;
                foreach ($data_day as $item) {
                    $penalizable_inductive_day = $penalizable_inductive_day + $item->penalizable_reactive_inductive_consumption;
                    $penalizable_capacitive_day = $penalizable_capacitive_day + $item->penalizable_reactive_capacitive_consumption;
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
                $a = DailyMicrocontrollerData::updateOrCreate(
                    [
                        'year' => $this->day_reference->format('Y'),
                        'month' => $this->day_reference->format('m'),
                        'day' => $this->day_reference->format('d'),
                        'client_id' => $this->client->id],
                    ['microcontroller_data_id' => $reference_data->id,
                        'interval_real_consumption' => $interval_active_day,
                        'interval_reactive_capacitive_consumption' => $interval_capacitive_day,
                        'interval_reactive_inductive_consumption' => $interval_inductive_day,
                        'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_day,
                        'penalizable_reactive_inductive_consumption' => $penalizable_inductive_day,
                        'raw_json' => json_encode($json)
                    ]);
            }
        } else {
            $last_day = $this->day_reference->copy()->subDay();
            $last_data = $this->client->hourlyMicrocontrollerData()
                ->where('year', $last_day->format('Y'))
                ->where('month', $last_day->format('m'))
                ->where('day', $last_day->format('d'))
                ->orderBy('source_timestamp', 'desc')->first();
            if ($last_data == null) {
                $last_data = $this->client->hourlyMicrocontrollerData()
                    ->whereBetween('source_timestamp', [$last_day->copy()->subDays(15)->format('Y-m-d H:00:00'), $last_day->format('Y-m-d 23:59:59')])
                    ->orderBy('source_timestamp', 'desc')
                    ->first();
            }
            if ($last_data) {
                $data_frame = config('data-frame.data_frame');
                $raw_json = json_decode($last_data->raw_json, true);
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
                DailyMicrocontrollerData::updateOrCreate(
                    ['year' => $this->day_reference->format('Y'),
                        'month' => $this->day_reference->format('m'),
                        'day' => $this->day_reference->format('d'),
                        'client_id' => $this->client->id],
                    ['microcontroller_data_id' => $last_data->microcontroller_data_id,
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

        $day_data = $this->client->dailyMicrocontrollerdata()
            ->where('year', $this->day_reference->format('Y'))
            ->where('month', $this->day_reference->format('m'))
            ->where('day', $this->day_reference->format('d'))->first();
        if ($day_data) {
            $year = $this->day_reference->copy()->subDay()->format('Y');
            $month = $this->day_reference->copy()->subDay()->format('m');
            $day = $this->day_reference->copy()->subDay()->format('d');
            $hour = $this->day_reference->copy()->subDay()->format('H');
            if ($day_data->interval_real_consumption != 0) {
                $last_raw_json = json_decode($day_data->raw_json, true);
                $previous_day_data = $this->client->dailyMicrocontrollerdata()
                    ->where('year', $year)
                    ->where('month', $month)
                    ->where('day', $day)
                    ->first();
                if ($previous_day_data == null) {
                    $previous_day_data = $this->client->dailyMicrocontrollerData()
                        ->whereHas('microcontrollerData', function ($query) {
                            $query->whereBetween("source_timestamp", [$this->day_reference->copy()->subDays(15)->format('Y-m-d 00:00:00'), $this->day_reference->copy()->subDay()->format('Y-m-d 23:59:59')]);
                        })
                        ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                        ->first();
                }
                if ($previous_day_data) {
                    if ($previous_day_data->microcontroller_data_id != $day_data->microcontroller_data_id) {
                        $data = DailyMicrocontrollerData::whereMicrocontrollerDataId($previous_day_data->microcontroller_data_id)->orderBy('year')->orderBy('month')->orderBy('day')->get();
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
                                    //$datum->accumulated_real_consumption = $raw_json['import_wh'];
                                    $datum->interval_real_consumption = $raw_json['kwh_interval'];
                                    //$datum->accumulated_reactive_consumption = $raw_json['import_VArh'];
                                    $datum->interval_reactive_consumption = $raw_json['varh_interval'];
                                    //$datum->accumulated_reactive_capacitive_consumption = $raw_json['varCh_acumm'];
                                    //$datum->accumulated_reactive_inductive_consumption = $raw_json['varLh_acumm'];
                                    $datum->interval_reactive_capacitive_consumption = $raw_json['varCh_interval'];
                                    $datum->interval_reactive_inductive_consumption = $raw_json['varLh_interval'];
                                    if ($datum->interval_real_consumption == 0) {
                                        $penalizable_inductive = $datum->interval_reactive_inductive_consumption;
                                    } else {
                                        $percent_penalizable_inductive = ($datum->interval_reactive_inductive_consumption * 100) / $datum->interval_real_consumption;
                                        if ($percent_penalizable_inductive >= 50) {
                                            $penalizable_inductive = ($datum->interval_real_consumption * $percent_penalizable_inductive / 100) - ($datum->interval_real_consumption * 0.5);
                                        } else {
                                            $penalizable_inductive = 0;
                                        }
                                    }
                                    $datum->penalizable_reactive_inductive_consumption = $penalizable_inductive;
                                    $datum->penalizable_reactive_capacitive_consumption = $datum->interval_reactive_capacitive_consumption;
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
                            $reference_data->raw_json = json_encode($raw_json);
                            //$reference_data->accumulated_real_consumption = $raw_json['import_wh'];
                            $reference_data->interval_real_consumption = $raw_json['kwh_interval'];
                            //$reference_data->accumulated_reactive_consumption = $raw_json['import_VArh'];
                            $reference_data->interval_reactive_consumption = $raw_json['varh_interval'];
                            //$reference_data->accumulated_reactive_capacitive_consumption = $raw_json['varCh_acumm'];
                            //$reference_data->accumulated_reactive_inductive_consumption = $raw_json['varLh_acumm'];
                            $reference_data->interval_reactive_capacitive_consumption = $raw_json['varCh_interval'];
                            $reference_data->interval_reactive_inductive_consumption = $raw_json['varLh_interval'];
                            if ($reference_data->interval_real_consumption == 0) {
                                $penalizable_inductive = $reference_data->interval_reactive_inductive_consumption;
                            } else {
                                $percent_penalizable_inductive = ($reference_data->interval_reactive_inductive_consumption * 100) / $reference_data->interval_real_consumption;
                                if ($percent_penalizable_inductive >= 50) {
                                    $penalizable_inductive = ($reference_data->interval_real_consumption * $percent_penalizable_inductive / 100) - ($reference_data->interval_real_consumption * 0.5);
                                } else {
                                    $penalizable_inductive = 0;
                                }
                            }
                            $reference_data->penalizable_reactive_inductive_consumption = $penalizable_inductive;
                            $reference_data->penalizable_reactive_capacitive_consumption = $reference_data->interval_reactive_capacitive_consumption;
                            $reference_data->save();
                        }
                    }
                }
            }
        }
    }
}
