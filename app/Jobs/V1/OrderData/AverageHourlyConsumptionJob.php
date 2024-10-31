<?php

namespace App\Jobs\V1\OrderData;

use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AverageHourlyConsumptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $client;
    public $hour_reference;

    public function __construct($client_id, Carbon $hour_reference)
    {
        $this->client = \App\Models\V1\Client::find($client_id);
        $this->hour_reference = $hour_reference;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $year = $this->hour_reference->format('Y');
        $month = $this->hour_reference->format('m');
        $day = $this->hour_reference->format('d');
        $hour = $this->hour_reference->format('H');
        $reference_data = MicrocontrollerData::whereClientId($this->client->id)
            ->whereBetween("source_timestamp", [$this->hour_reference->format('Y-m-d H:00:00'), $this->hour_reference->format('Y-m-d H:59:59')])
            ->orderBy('source_timestamp', 'desc')
            ->first();
        if ($reference_data) {
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
            $reference_data = HourlyMicrocontrollerData::updateOrCreate(
                ['year' => $year,
                    'month' => $month,
                    'day' => $day,
                    'hour' => $hour,
                    'client_id' => $reference_data->client_id],
                ['microcontroller_data_id' => $reference_data->id,
                    'interval_real_consumption' => $reference_data->interval_real_consumption,
                    'interval_reactive_capacitive_consumption' => $reference_data->interval_reactive_capacitive_consumption,
                    'interval_reactive_inductive_consumption' => $reference_data->interval_reactive_inductive_consumption,
                    'penalizable_reactive_capacitive_consumption' => $reference_data->interval_reactive_capacitive_consumption,
                    'penalizable_reactive_inductive_consumption' => $penalizable_inductive,
                    'source_timestamp' => $reference_data->source_timestamp,
                    'raw_json' => $reference_data->raw_json]
            );
        } else {
            $previous_hour = $this->hour_reference->copy()->subHour();
            $previous_hour_data = MicrocontrollerData::whereClientId($this->client->id)
                ->whereBetween('source_timestamp', [$previous_hour->format('Y-m-d H:00:00'), $previous_hour->format('Y-m-d H:59:59')])
                ->orderBy('source_timestamp', 'desc')
                ->first();
            $data_frame = config('data-frame.data_frame');
            if ($previous_hour_data == null) ;
            {
                $previous_hour_data = MicrocontrollerData::whereClientId($this->client->id)
                    ->whereBetween('source_timestamp', [$previous_hour->copy()->subDays(15)->format('Y-m-d H:00:00'), $previous_hour->format('Y-m-d H:59:59')])
                    ->orderBy('source_timestamp', 'desc')
                    ->first();
            }
            if ($previous_hour_data) {
                $raw_json = json_decode($previous_hour_data->raw_json, true);
                if ($raw_json != null) {
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
                    $source_timestamp = new Carbon($previous_hour_data->source_timestamp);
                    $reference_data = HourlyMicrocontrollerData::updateOrCreate(
                        ['year' => $year,
                            'month' => $month,
                            'day' => $day,
                            'hour' => $hour,
                            'client_id' => $this->client->id],
                        ['microcontroller_data_id' => $previous_hour_data->id,
                            'interval_real_consumption' => 0,
                            'interval_reactive_capacitive_consumption' => 0,
                            'interval_reactive_inductive_consumption' => 0,
                            'penalizable_reactive_capacitive_consumption' => 0,
                            'penalizable_reactive_inductive_consumption' => 0,
                            'source_timestamp' => $this->hour_reference->format('Y-m-d H:59:59'),
                            'raw_json' => json_encode($raw_json),
                        ]
                    );
                }
            }
        }
        $reference_data = HourlyMicrocontrollerData::whereClientId($this->client->id)
            ->whereBetween('source_timestamp', [$this->hour_reference->format('Y-m-d H:00:00'), $this->hour_reference->format('Y-m-d H:59:59')])
            ->first();
        if ($reference_data) {
            if ($reference_data->interval_real_consumption != 0) {
                $last_raw_json = json_decode($reference_data->raw_json, true);
                $previous_hour_data = $this->client->hourlyMicrocontrollerdata()
                    ->whereBetween('source_timestamp', [$this->hour_reference->copy()->subHour()->format('Y-m-d H:00:00'), $this->hour_reference->copy()->subHour()->format('Y-m-d H:59:59')])
                    ->first();
                if ($previous_hour_data == null) {
                    $previous_hour_data = $this->client->hourlyMicrocontrollerdata()
                        ->whereBetween('source_timestamp', [$this->hour_reference->copy()->subDays(15)->format('Y-m-d H:00:00'), $this->hour_reference->copy()->subHour()->format('Y-m-d H:59:59')])
                        ->orderBy('year', 'desc')->orderBy('month', 'desc')->orderBy('day', 'desc')
                        ->first();
                }
                if ($previous_hour_data) {
                    if ($previous_hour_data->microcontroller_data_id != $reference_data->microcontroller_data_id) {
                        $data = HourlyMicrocontrollerData::whereMicrocontrollerDataId($previous_hour_data->microcontroller_data_id)
                            ->orderBy('source_timestamp')->orderBy('year')->orderBy('month')->orderBy('day')
                            ->get();
                        if (count($data) > 1) {
                            $i = 0;
                            foreach ($data as $datum) {
                                if ($i == 0) {
                                    $first_raw_json = json_decode($datum->raw_json, true);
                                    $average_accumulated_real_consumption = ($last_raw_json['import_wh'] - $first_raw_json['import_wh']) / count($data);
                                    if ($average_accumulated_real_consumption<0){$average_accumulated_real_consumption = 0;}
                                    $average_accumulated_real_consumption_ph1 = ($last_raw_json['ph1_import_kwh'] - $first_raw_json['ph1_import_kwh']) / count($data);
                                    if ($average_accumulated_real_consumption_ph1<0){$average_accumulated_real_consumption_ph1 = 0;}
                                    $average_accumulated_real_consumption_ph2 = ($last_raw_json['ph2_import_kwh'] - $first_raw_json['ph2_import_kwh']) / count($data);
                                    if ($average_accumulated_real_consumption_ph2<0){$average_accumulated_real_consumption_ph2 = 0;}
                                    $average_accumulated_real_consumption_ph3 = ($last_raw_json['ph3_import_kwh'] - $first_raw_json['ph3_import_kwh']) / count($data);
                                    if ($average_accumulated_real_consumption_ph3<0){$average_accumulated_real_consumption_ph3 = 0;}
                                    $average_accumulated_reactive_consumption = ($last_raw_json['import_VArh'] - $first_raw_json['import_VArh']) / count($data);
                                    if ($average_accumulated_reactive_consumption<0){$average_accumulated_reactive_consumption = 0;}
                                    $average_accumulated_reactive_consumption_ph1 = ($last_raw_json['ph1_import_kvarh'] - $first_raw_json['ph1_import_kvarh']) / count($data);
                                    if ($average_accumulated_reactive_consumption_ph1<0){$average_accumulated_reactive_consumption_ph1 = 0;}
                                    $average_accumulated_reactive_consumption_ph2 = ($last_raw_json['ph2_import_kvarh'] - $first_raw_json['ph2_import_kvarh']) / count($data);
                                    if ($average_accumulated_reactive_consumption_ph2<0){$average_accumulated_reactive_consumption_ph2 = 0;}
                                    $average_accumulated_reactive_consumption_ph3 = ($last_raw_json['ph3_import_kvarh'] - $first_raw_json['ph3_import_kvarh']) / count($data);
                                    if ($average_accumulated_reactive_consumption_ph3<0){$average_accumulated_reactive_consumption_ph3 = 0;}
                                    $average_accumulated_reactive_inductive_consumption = ($last_raw_json['varLh_acumm'] - $first_raw_json['varLh_acumm']) / count($data);
                                    if ($average_accumulated_reactive_inductive_consumption<0){$average_accumulated_reactive_inductive_consumption = 0;}
                                    $average_accumulated_reactive_inductive_consumption_ph1 = ($last_raw_json['ph1_varLh_acumm'] - $first_raw_json['ph1_varLh_acumm']) / count($data);
                                    if ($average_accumulated_reactive_inductive_consumption_ph1<0){$average_accumulated_reactive_inductive_consumption_ph1 = 0;}
                                    $average_accumulated_reactive_inductive_consumption_ph2 = ($last_raw_json['ph2_varLh_acumm'] - $first_raw_json['ph2_varLh_acumm']) / count($data);
                                    if ($average_accumulated_reactive_inductive_consumption_ph2<0){$average_accumulated_reactive_inductive_consumption_ph2 = 0;}
                                    $average_accumulated_reactive_inductive_consumption_ph3 = ($last_raw_json['ph3_varLh_acumm'] - $first_raw_json['ph3_varLh_acumm']) / count($data);
                                    if ($average_accumulated_reactive_inductive_consumption_ph3<0){$average_accumulated_reactive_inductive_consumption_ph3 = 0;}
                                    $average_accumulated_reactive_capacitive_consumption = ($last_raw_json['varCh_acumm'] - $first_raw_json['varCh_acumm']) / count($data);
                                    if ($average_accumulated_reactive_capacitive_consumption<0){$average_accumulated_reactive_capacitive_consumption = 0;}
                                    $average_accumulated_reactive_capacitive_consumption_ph1 = ($last_raw_json['ph1_varCh_acumm'] - $first_raw_json['ph1_varCh_acumm']) / count($data);
                                    if ($average_accumulated_reactive_capacitive_consumption_ph1<0){$average_accumulated_reactive_capacitive_consumption_ph1 = 0;}
                                    $average_accumulated_reactive_capacitive_consumption_ph2 = ($last_raw_json['ph2_varCh_acumm'] - $first_raw_json['ph2_varCh_acumm']) / count($data);
                                    if ($average_accumulated_reactive_capacitive_consumption_ph2<0){$average_accumulated_reactive_capacitive_consumption_ph2 = 0;}
                                    $average_accumulated_reactive_capacitive_consumption_ph3 = ($last_raw_json['ph3_varCh_acumm'] - $first_raw_json['ph3_varCh_acumm']) / count($data);
                                    if ($average_accumulated_reactive_capacitive_consumption_ph3<0){$average_accumulated_reactive_capacitive_consumption_ph3 = 0;}
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
                                    $datum->interval_real_consumption = $raw_json['kwh_interval'];
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
                            $reference_data->interval_real_consumption = $raw_json['kwh_interval'];
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
