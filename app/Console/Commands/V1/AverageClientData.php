<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use App\Models\V1\HourlyMicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AverageClientData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:average_client_data';

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
        $end_date = Carbon::create(2022, 07, 16, 11, 0, 0);
        while (true) {
            $reference_date->subHour();
            echo $reference_date->format('Y-m-d H') . "\n";
            foreach ($clients as $client) {
                $year = $reference_date->format('Y');
                $month = $reference_date->format('m');
                $day = $reference_date->format('d');
                $hour = $reference_date->format('H');
                $hour_data = $client->hourlyMicrocontrollerdata()
                    ->whereYear($year)
                    ->whereMonth($month)
                    ->whereDay($day)
                    ->whereHour($hour)
                    ->first();
                $last_raw_json = json_decode($hour_data->raw_json, true);
                if ($hour_data) {
                    $previous_hour_data = $client->hourlyMicrocontrollerdata()
                        ->whereYear($end_date->copy()->subHour()->format('Y'))
                        ->whereMonth($end_date->copy()->subHour()->format('m'))
                        ->whereDay($end_date->copy()->subHour()->format('d'))
                        ->whereHour($end_date->copy()->subHour()->format('h'))
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
                                        $datum->interval_real_cunsumption = $raw_json['kwh_interval'];
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
                                $hour_data->interval_real_cunsumption = $raw_json['kwh_interval'];
                                $hour_data->save();
                            }
                        }
                    }
                }
            }
            if ($end_date->diffInHours($reference_date) == 0) {
                break;
            }
        }
    }
}
