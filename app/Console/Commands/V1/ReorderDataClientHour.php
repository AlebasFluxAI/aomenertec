<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use App\Models\V1\HourlyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReorderDataClientHour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:reorder_hourly_data_client';

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
        $reference_date = new Carbon();
        $end_date = Carbon::create(2024, 05, 25, 0, 0, 0);
        $end_date_copy = $end_date->copy();
        $data_frame = config('data-frame.data_frame');
        while (true) {
            $end_date->addHour();
            echo $end_date->format('Y-m-d H') . "\n";
            foreach ($clients as $client) {
                $year = $end_date->format('Y');
                $month = $end_date->format('m');
                $day = $end_date->format('d');
                $hour = $end_date->format('H');
                $hour_data_client = $client->hourlyMicrocontrollerData()
                    ->where([
                        ['year', '=', $year],
                        ['month', '=', $month],
                        ['day', '=', $day],
                        ['hour', '=', $hour],
                    ])->get();
                if (count($hour_data_client) > 0) {
                    foreach ($hour_data_client as $datum) {
                        $datum->forceDelete();
                    }
                }
                $reference_data = $client->microcontrollerData()
                    ->whereBetween("source_timestamp", [$end_date->format('Y-m-d H:00:00'), $end_date->format('Y-m-d H:59:59')])
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
                    HourlyMicrocontrollerData::updateOrCreate(
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
                    $last_hour = $end_date->copy()->subHour();
                    $last_data = $client->hourlyMicrocontrollerData()
                        ->where('year', $last_hour->format('Y'))
                        ->where('month', $last_hour->format('m'))
                        ->where('day', $last_hour->format('d'))
                        ->where('hour', $last_hour->format('H'))->first();
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
                        $source_timestamp = new Carbon($last_data->source_timestamp);
                        HourlyMicrocontrollerData::updateOrCreate(
                            ['year' => $year,
                                'month' => $month,
                                'day' => $day,
                                'hour' => $hour,
                                'client_id' => $client->id],
                            ['microcontroller_data_id' => $last_data->microcontroller_data_id,
                                'interval_real_consumption' => 0,
                                'interval_reactive_capacitive_consumption' => 0,
                                'interval_reactive_inductive_consumption' => 0,
                                'penalizable_reactive_capacitive_consumption' => 0,
                                'penalizable_reactive_inductive_consumption' => 0,
                                'source_timestamp' => $source_timestamp->addHour(),
                                'raw_json' => json_encode($raw_json),
                            ]
                        );
                    }
                }
            }
            if ($end_date->diffInHours($reference_date) == 0) {
                break;
            }
        }
        while (true) {
            $reference_date->subHour();
            foreach ($clients as $client) {
                $year = $reference_date->format('Y');
                $month = $reference_date->format('m');
                $day = $reference_date->format('d');
                $hour = $reference_date->format('H');
                $hour_data = $client->hourlyMicrocontrollerdata()
                    ->where('year', $year)
                    ->where('month', $month)
                    ->where('day', $day)
                    ->where('hour', $hour)
                    ->first();
                if ($hour_data) {
                    $last_raw_json = json_decode($hour_data->raw_json, true);
                    $previous_hour_data = $client->hourlyMicrocontrollerdata()
                        ->whereBetween('source_timestamp', [$reference_date->copy()->subHour()->format('Y-m-d H:00:00'), $reference_date->copy()->subHour()->format('Y-m-d H:59:59')])
                        ->first();
                    if ($previous_hour_data) {
                        if ($previous_hour_data->interval_real_consumption == 0) {
                            $data = HourlyMicrocontrollerData::whereMicrocontrollerDataId($previous_hour_data->microcontroller_data_id)->orderBy('source_timestamp')->get();
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
                                $hour_data->raw_json = json_encode($raw_json);
                                $hour_data->interval_real_consumption = $raw_json['kwh_interval'];
                                $hour_data->save();
                            }
                        }
                    }
                }
            }
            if ($end_date_copy->diffInHours($reference_date) == 0) {
                break;
            }
        }
    }
}
