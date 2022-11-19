<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use App\Models\V1\DailyMicrocontrollerData;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
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
        $end_date= Carbon::create(2022,07,16, 11,0,0);
        $data_frame = config('data-frame.data_frame');
        while (true) {
            $end_date->addHour();
            echo $end_date->format('Y-m-d H')."\n";
            foreach ($clients as $client) {
                $year =  $end_date->format('Y');
                $month = $end_date->format('m');
                $day =   $end_date->format('d');
                $hour =  $end_date->format('H');
                if ($client->microcontrollerData()
                    ->whereBetween("source_timestamp", [$end_date->format('Y-m-d H:00:00'), $end_date->format('Y-m-d H:59:59')])->exists()) {
                    $reference_data = $client->microcontrollerData()
                        ->whereBetween("source_timestamp", [$end_date->format('Y-m-d H:00:00'), $end_date->format('Y-m-d H:59:59')])
                        ->orderBy('source_timestamp', 'desc')
                        ->first();


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
                        foreach ($data_frame as $item){
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
            if ($end_date->diffInHours($reference_date)==0){
                break;
            }
        }
    }
}
