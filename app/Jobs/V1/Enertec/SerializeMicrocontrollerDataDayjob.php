<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\Client;
use App\Models\V1\DailyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SerializeMicrocontrollerDataDayjob implements ShouldQueue
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
        $clients = Client::whereHasTelemetry(true)->get();
        $data_frame = config('data-frame.data_frame');
        foreach ($clients as $client) {

            if ($client->microcontrollerData()
                ->whereBetween('source_timestamp', [$this->day_ref->format('Y-m-d 00:00:00'), $this->day_ref->format('Y-m-d 23:59:59')])->exists()) {
                $data_day = $client->hourlyMicrocontrollerData()
                    ->where('year', $this->day_ref->format('Y'))
                    ->where('month', $this->day_ref->format('m'))
                    ->where('day', $this->day_ref->format('d'))->get();
                $reference_data = $client->microcontrollerData()
                    ->whereBetween('source_timestamp', [$this->day_ref->format('Y-m-d 00:00:00'), $this->day_ref->format('Y-m-d 23:59:59')])
                    ->orderBy('source_timestamp', 'desc')
                    ->first();

                if ($client->microcontrollerData()
                    ->whereBetween('source_timestamp', [$this->day_ref->copy()->subDay()->format('Y-m-d 00:00:00'), $this->day_ref->copy()->subDay()->format('Y-m-d 23:59:59')])->exists()) {
                    $reference_data_first = $client->microcontrollerData()
                        ->whereBetween('source_timestamp', [$this->day_ref->copy()->subDay()->format('Y-m-d 00:00:00'), $this->day_ref->copy()->subDay()->format('Y-m-d 23:59:59')])
                        ->orderBy('source_timestamp', 'desc')
                        ->first();
                } else {
                    if ($client->microcontrollerData()
                        ->where('source_timestamp', '<', $this->day_ref->format('Y-m-d 00:00:00'))->exists()) {
                        $reference_data_first = $client->microcontrollerData()
                            ->where('source_timestamp', '<', $this->day_ref->format('Y-m-d 00:00:00'))
                            ->orderBy('source_timestamp', 'desc')
                            ->first();
                    } else {
                        $reference_data_first = $client->microcontrollerData()
                            ->whereBetween('source_timestamp', [$this->day_ref->format('Y-m-d 00:00:00'), $this->day_ref->format('Y-m-d 23:59:59')])
                            ->orderBy('source_timestamp')
                            ->first();
                    }
                }
                if ($reference_data) {
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

                    DailyMicrocontrollerData::updateOrCreate(
                        [
                            'year' => $this->day_ref->format('Y'),
                            'month' => $this->day_ref->format('m'),
                            'day' => $this->day_ref->format('d'),
                            'client_id' => $client->id],
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
                $last_day = $this->day_ref->copy()->subDay();
                $last_data = $client->hourlyMicrocontrollerData()
                    ->where('year', $last_day->format('Y'))
                    ->where('month', $last_day->format('m'))
                    ->where('day', $last_day->format('d'))->first();
                if ($last_data) {
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
                    $raw_json['data_ph1_varCh_acumm'] = 0;
                    $raw_json['data_ph2_varCh_acumm'] = 0;
                    $raw_json['data_ph3_varCh_acumm'] = 0;
                    $raw_json['data_ph1_varLh_acumm'] = 0;
                    $raw_json['data_ph2_varLh_acumm'] = 0;
                    $raw_json['data_ph3_varLh_acumm'] = 0;

                    DailyMicrocontrollerData::updateOrCreate(
                        ['year' => $this->day_ref->format('Y'),
                            'month' => $this->day_ref->format('m'),
                            'day' => $this->day_ref->format('d'),
                            'client_id' => $client->id],
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
            $year = $this->day_ref->format('Y');
            $month = $this->day_ref->format('m');
            $day = $this->day_ref->format('d');
            $hour = $this->day_ref->format('H');
            $day_data = $client->dailyMicrocontrollerdata()
                ->where('year', $year)
                ->where('month', $month)
                ->where('day', $day)->first();
            if ($day_data) {
                if ($day_data->interval_real_consumption != 0) {
                    $last_raw_json = json_decode($day_data->raw_json, true);
                    $previous_day_data = $client->dailyMicrocontrollerdata()
                        ->where('year', $year)
                        ->where('month', $month)
                        ->where('day', $day)
                        ->first();
                    if ($previous_day_data) {
                        if ($previous_day_data->interval_real_consumption == 0) {
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
                                        $datum->raw_json = json_encode($raw_json);
                                        $datum->interval_real_consumption = $raw_json['kwh_interval'];
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
                                $day_data->raw_json = json_encode($raw_json);
                                $day_data->interval_real_consumption = $raw_json['kwh_interval'];
                                $day_data->save();
                            }
                        }
                    }
                }
            }
        }
    }
}
