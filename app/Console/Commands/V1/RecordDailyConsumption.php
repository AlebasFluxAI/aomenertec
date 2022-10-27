<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\AuxData;
use App\Models\V1\Client;
use App\Models\V1\DailyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RecordDailyConsumption extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:enertec:v1:record_daily_consumption';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will run every day at 00:03 am recording daily consumption to clients';

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
        $data_frame = collect(config('data-frame.data_frame'));
        $accum_variable = $data_frame->where('bolean_accum', true);
        $null_data = DailyMicrocontrollerData::whereNull('microcontroller_data_id')->get();
        if ($null_data) {
            foreach ($null_data as $data) {
                $data_day = $data->client->hourlyMicrocontrollerData()
                    ->where('year', $data->year)
                    ->where('month', $data->month)
                    ->where('day', $data->day)->get();
                if (count($data_day) > 0) {
                    $reference_date = Carbon::create($data->year, $data->month, $data->day);
                    $reference_data = $data->client->microcontrollerData()
                        ->whereDate('source_timestamp', $reference_date->format('Y-m-d'))
                        ->orderBy('source_timestamp', 'desc')
                        ->first();
                    $reference_data_first = $data->client->microcontrollerData()
                        ->whereDate('source_timestamp', $reference_date->format('Y-m-d'))
                        ->orderBy('source_timestamp')
                        ->first();
                    $json = json_decode($reference_data->raw_json, true);
                    $penalizable_inductive_day = 0;
                    $penalizable_capacitive_day = 0;
                    $interval_active_day = $reference_data->accumulated_real_consumption - $reference_data_first->accumulated_real_consumption;
                    $interval_capacitive_day = $reference_data->accumulated_reactive_capacitive_consumption - $reference_data_first->accumulated_reactive_capacitive_consumption;
                    $interval_inductive_day = $reference_data->accumulated_reactive_inductive_consumption - $reference_data_first->accumulated_reactive_inductive_consumption;
                    foreach ($data_day as $item) {
                        $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                        foreach ($accum_variable as $index => $variable) {
                            if ($item->microcontroller_data_id != $reference_data->id) {
                                $json[$variable['variable_name']] = $json[$variable['variable_name']] + $raw_json[$variable['variable_name']];
                            }
                        }
                        $penalizable_inductive_day = $penalizable_inductive_day + $item->penalizable_reactive_inductive_consumption;
                        $penalizable_capacitive_day = $penalizable_capacitive_day + $item->penalizable_reactive_capacitive_consumption;
                    }
                    $data->microcontroller_data_id = $reference_data->id;
                    $data->interval_real_consumption = $interval_active_day;
                    $data->interval_reactive_capacitive_consumption = $interval_capacitive_day;
                    $data->interval_reactive_inductive_consumption = $interval_inductive_day;
                    $data->penalizable_reactive_capacitive_consumption = $penalizable_capacitive_day;
                    $data->penalizable_reactive_inductive_consumption = $penalizable_inductive_day;
                    $data->raw_json = json_encode($json);
                    $data->save();
                }
            }
        }
        $clients = Client::whereHasTelemetry(true)->get();
        $reference_date = new Carbon();
        $aux_date = new Carbon();
        $aux_date->subDays(30);
        $null_data = DailyMicrocontrollerData::whereNull('microcontroller_data_id')
            ->whereDate('created_at', '<', $aux_date->format('Y-m-d'))
            ->get();
        foreach ($null_data as $data) {
            $data->delete();
        }
        $i = 0;
        $reference_date->subDay();
        foreach ($clients as $client) {
            $data_day = $client->hourlyMicrocontrollerData()
                ->where('year', $reference_date->format('Y'))
                ->where('month', $reference_date->format('m'))
                ->where('day', $reference_date->format('d'))->get();

            if (count($data_day) > 0) {
                $reference_data = $client->microcontrollerData()
                    ->whereDate('source_timestamp', $reference_date->format('Y-m-d'))
                    ->orderBy('source_timestamp', 'desc')
                    ->first();
                $reference_data_first = $client->microcontrollerData()
                    ->whereDate('source_timestamp', $reference_date->format('Y-m-d'))
                    ->orderBy('source_timestamp')
                    ->first();
                $json = json_decode($reference_data->raw_json, true);
                $penalizable_inductive_day = 0;
                $penalizable_capacitive_day = 0;
                $interval_active_day = $reference_data->accumulated_real_consumption - $reference_data_first->accumulated_real_consumption;
                $interval_capacitive_day = $reference_data->accumulated_reactive_capacitive_consumption - $reference_data_first->accumulated_reactive_capacitive_consumption;
                $interval_inductive_day = $reference_data->accumulated_reactive_inductive_consumption - $reference_data_first->accumulated_reactive_inductive_consumption;
                foreach ($data_day as $item) {
                    if ($item->microcontrollerData) {
                        $raw_json = json_decode($item->microcontrollerData->raw_json, true);
                        foreach ($accum_variable as $index => $variable) {
                            if ($item->microcontroller_data_id != $reference_data->id) {
                                $json[$variable['variable_name']] = $json[$variable['variable_name']] + $raw_json[$variable['variable_name']];
                            }
                        }
                        $penalizable_inductive_day = $penalizable_inductive_day + $item->penalizable_reactive_inductive_consumption;
                        $penalizable_capacitive_day = $penalizable_capacitive_day + $item->penalizable_reactive_capacitive_consumption;
                    }
                }
                DailyMicrocontrollerData::create([
                    'year' => $reference_date->format('Y'),
                    'month' => $reference_date->format('m'),
                    'day' => $reference_date->format('d'),
                    'client_id' => $client->id,
                    'microcontroller_data_id' => $reference_data->id,
                    'interval_real_consumption' => $interval_active_day,
                    'interval_reactive_capacitive_consumption' => $interval_capacitive_day,
                    'interval_reactive_inductive_consumption' => $interval_inductive_day,
                    'penalizable_reactive_capacitive_consumption' => $penalizable_capacitive_day,
                    'penalizable_reactive_inductive_consumption' => $penalizable_inductive_day,
                    'raw_json' => json_encode($json),
                ]);
            } else {
                DailyMicrocontrollerData::create([
                    'year' => $reference_date->format('Y'),
                    'month' => $reference_date->format('m'),
                    'day' => $reference_date->format('d'),
                    'client_id' => $client->id
                ]);
            }
        }
    }
}
