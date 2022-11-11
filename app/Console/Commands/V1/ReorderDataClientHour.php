<?php

namespace App\Console\Commands\V1;

use App\Models\V1\Client;
use App\Models\V1\DailyMicrocontrollerData;
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
    protected $signature = 'command:name';

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
        $data_frame = collect(config('data-frame.data_frame'));
        $accum_variable = $data_frame->where('bolean_accum', true);
        $reference_date = new Carbon();
        $end_date= Carbon::create(2022,07,16, 12);
        while (true) {
            $reference_date->subHour();
            echo $reference_date->format('Y-m-d H')."\n";
            foreach ($clients as $client) {
                $data_hour = $client->microcontrollerData()
                    ->whereBetween("source_timestamp", [$reference_date->format('Y-m-d H:00:00'), $reference_date->format('Y-m-d H:59:59')])->get();
                $year =  $reference_date->format('Y');
                $month = $reference_date->format('m');
                $day =   $reference_date->format('d');
                $hour =  $reference_date->format('H');
                if (count($data_hour) > 0) {
                    $reference_data = $client->microcontrollerData()
                        ->whereBetween("source_timestamp", [$reference_date->format('Y-m-d H:00:00'), $reference_date->format('Y-m-d H:59:59')])
                        ->orderBy('source_timestamp', 'desc')
                        ->first();
                    $json = json_decode($reference_data->raw_json, true);

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
                            'penalizable_reactive_inductive_consumption' => $penalizable_inductive]
                    );
                } else {
                    $last_hour = $reference_date->copy()->subHour();
                    $last_data = $client->hourlyMicrocontrollerData()
                                                        ->whereYear($last_hour->format('Y'))
                                                        ->whereMonth($last_hour->format('m'))
                                                        ->whereDay($last_hour->format('d'))
                                                        ->whereHour($last_hour->format('H'))->first();
                    HourlyMicrocontrollerData::updateOrCreate(
                        ['year' => $year,
                            'month' => $month,
                            'day' => $day,
                            'hour' => $hour,
                            'client_id' => $client->id],
                        ['microcontroller_data_id' => $last_data->id,
                            'interval_real_consumption' => 0,
                            'interval_reactive_capacitive_consumption' => 0,
                            'interval_reactive_inductive_consumption' => 0,
                            'penalizable_reactive_capacitive_consumption' => 0,
                            'penalizable_reactive_inductive_consumption' => 0]
                    );
                }
            }
            if ($reference_date->diffInHours($end_date)==0){
                break;
            }
        }
    }
}
