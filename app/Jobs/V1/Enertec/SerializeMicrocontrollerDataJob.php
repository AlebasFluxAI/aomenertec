<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\EquipmentType;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PHPUnit\Exception;

class SerializeMicrocontrollerDataJob implements ShouldQueue
{
    use Batchable,Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $model;
    public function __construct(MicrocontrollerData $model)
    {
        $this->model = $model->withoutRelations();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->jsonEdit();

    }

    public function jsonEdit()
    {
        try {
            if (is_string($this->raw_json)) {
                $json = json_decode($this->raw_json, true);
            } elseif (is_array($this->raw_json)) {
                $json = $this->raw_json;
            }
            $current_time = new Carbon($this->model->source_timestamp);
            $equipment_serial = str_pad($json['equipment_id'], 6, "0", STR_PAD_LEFT);
            $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                ->first();
            if ($equipment == null) {
                $this->model->forceDelete();
                return;
            }
            $client = $equipment->clients()->first();

            if ($client == null) {
                $this->model->forceDelete();
                return;
            }

            if ($client->microcontrollerData()->where('source_timestamp', $current_time->format('Y-m-d H:i:s'))->exists()) {
                if ($this->model->hourlyMicrocontrollerData()->exists()){
                    $this->model->hourlyMicrocontrollerData()->forceDelete();
                }
                if ($this->model->dailyMicrocontrollerData()->exists()){
                    $this->model->dailyMicrocontrollerData()->forceDelete();
                }
                $this->model->forceDelete();
                return;
            }

            if (!MicrocontrollerData::whereClientId($client->id)->exists()) {
                $json['kwh_interval'] = 0;
                $json['varh_interval'] = 0;
                $json['varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($json['ph2_varCh_acumm']) + floatval($json['ph3_varCh_acumm']);
                $json['varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($json['ph2_varLh_acumm']) + floatval($json['ph3_varLh_acumm']);
                $json['ph1_varCh_interval'] = 0;
                $json['ph1_varLh_interval'] = 0;
                $json['ph2_varCh_interval'] = 0;
                $json['ph2_varLh_interval'] = 0;
                $json['ph3_varCh_interval'] = 0;
                $json['ph3_varLh_interval'] = 0;
                $json['ph1_kwh_interval'] = 0;
                $json['ph2_kwh_interval'] = 0;
                $json['ph3_kwh_interval'] = 0;
                $json['ph1_varh_interval'] = 0;
                $json['ph2_varh_interval'] = 0;
                $json['ph3_varh_interval'] = 0;
                $json['varCh_interval'] = 0;
                $json['varLh_interval'] = 0;
            } else {
                $last_data = $client->microcontrollerData()->where('source_timestamp', '<', $current_time->format('Y-m-d H:00:00'))->orderBy('source_timestamp', 'desc')->first();
                if ($last_data) {
                    $last_raw_json = json_decode($last_data->raw_json, true);
                    if ($json['import_wh'] <= 0) {

                        if ($last_raw_json['import_wh']>0) {
                            $this->model->forceDelete();
                            return;
                        }
                    }
                    if ($json['import_wh'] < $last_raw_json['import_wh']) {
                        $json['import_wh'] = $last_raw_json['import_wh'];
                    }
                    if ($json['import_VArh'] < $last_raw_json['import_VArh']) {
                        $json['import_VArh'] = $last_raw_json['import_VArh'];
                    }
                } else{
                    $last_data = $client->microcontrollerData()->orderBy('source_timestamp', 'desc')->first();
                    $last_raw_json = json_decode($last_data->raw_json, true);
                }
                $reference_hour = $current_time->copy()->subHour();
                $reference_data = $client->microcontrollerData()
                    ->whereBetween('source_timestamp', [$reference_hour->format('Y-m-d H:00:00'), $reference_hour->format('Y-m-d H:59:59')])
                    ->orderBy('source_timestamp', 'desc')
                    ->first();

                if (empty($reference_data)) {

                    $json['kwh_interval'] = $json['import_wh'] - $last_raw_json['import_wh'];
                    $json['varh_interval'] = $json['import_VArh'] - $last_raw_json['import_VArh'];
                    $json['varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($json['ph2_varCh_acumm']) + floatval($json['ph3_varCh_acumm']);
                    $json['varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($json['ph2_varLh_acumm']) + floatval($json['ph3_varLh_acumm']);
                    $json['ph1_varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($last_raw_json['ph1_varCh_acumm']);
                    $json['ph1_varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($last_raw_json['ph1_varLh_acumm']);
                    $json['ph2_varCh_acumm'] = floatval($json['ph2_varCh_acumm']) + floatval($last_raw_json['ph2_varCh_acumm']);
                    $json['ph2_varLh_acumm'] = floatval($json['ph2_varLh_acumm']) + floatval($last_raw_json['ph2_varLh_acumm']);
                    $json['ph3_varCh_acumm'] = floatval($json['ph3_varCh_acumm']) + floatval($last_raw_json['ph3_varCh_acumm']);
                    $json['ph3_varLh_acumm'] = floatval($json['ph3_varLh_acumm']) + floatval($last_raw_json['ph3_varLh_acumm']);
                    $json['varCh_acumm'] = $json['varCh_acumm'] + floatval($last_raw_json['varCh_acumm']);
                    $json['varLh_acumm'] = $json['varLh_acumm'] + floatval($last_raw_json['varLh_acumm']);
                    $json['ph1_varCh_interval'] = $json['ph1_varCh_acumm'] - floatval($last_raw_json['ph1_varCh_acumm']);
                    $json['ph1_varLh_interval'] = $json['ph1_varLh_acumm'] - floatval($last_raw_json['ph1_varLh_acumm']);
                    $json['ph2_varCh_interval'] = $json['ph2_varCh_acumm'] - floatval($last_raw_json['ph2_varCh_acumm']);
                    $json['ph2_varLh_interval'] = $json['ph2_varLh_acumm'] - floatval($last_raw_json['ph2_varLh_acumm']);
                    $json['ph3_varCh_interval'] = $json['ph3_varCh_acumm'] - floatval($last_raw_json['ph3_varCh_acumm']);
                    $json['ph3_varLh_interval'] = $json['ph3_varLh_acumm'] - floatval($last_raw_json['ph3_varLh_acumm']);
                    $json['ph1_kwh_interval'] = $json['ph1_import_kwh'] - $last_raw_json['ph1_import_kwh'];
                    $json['ph2_kwh_interval'] = $json['ph2_import_kwh'] - $last_raw_json['ph2_import_kwh'];
                    $json['ph3_kwh_interval'] = $json['ph3_import_kwh'] - $last_raw_json['ph3_import_kwh'];
                    $json['ph1_varh_interval'] = $json['ph1_import_kvarh'] - $last_raw_json['ph1_import_kvarh'];
                    $json['ph2_varh_interval'] = $json['ph2_import_kvarh'] - $last_raw_json['ph2_import_kvarh'];
                    $json['ph3_varh_interval'] = $json['ph3_import_kvarh'] - $last_raw_json['ph3_import_kvarh'];
                    $json['varCh_interval'] = $json['varCh_acumm'] - floatval($last_raw_json['varCh_acumm']);
                    $json['varLh_interval'] = $json['varLh_acumm'] - floatval($last_raw_json['varLh_acumm']);

                } else {

                    $reference_data_json = json_decode($reference_data->raw_json, true);
                    $json['kwh_interval'] = $json['import_wh'] - $reference_data_json['import_wh'];
                    $json['varh_interval'] = $json['import_VArh'] - $reference_data_json['import_VArh'];
                    $json['varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($json['ph2_varCh_acumm']) + floatval($json['ph3_varCh_acumm']);
                    $json['varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($json['ph2_varLh_acumm']) + floatval($json['ph3_varLh_acumm']);
                    $json['ph1_varCh_acumm'] = floatval($json['ph1_varCh_acumm']) + floatval($last_raw_json['ph1_varCh_acumm']);
                    $json['ph1_varLh_acumm'] = floatval($json['ph1_varLh_acumm']) + floatval($last_raw_json['ph1_varLh_acumm']);
                    $json['ph2_varCh_acumm'] = floatval($json['ph2_varCh_acumm']) + floatval($last_raw_json['ph2_varCh_acumm']);
                    $json['ph2_varLh_acumm'] = floatval($json['ph2_varLh_acumm']) + floatval($last_raw_json['ph2_varLh_acumm']);
                    $json['ph3_varCh_acumm'] = floatval($json['ph3_varCh_acumm']) + floatval($last_raw_json['ph3_varCh_acumm']);
                    $json['ph3_varLh_acumm'] = floatval($json['ph3_varLh_acumm']) + floatval($last_raw_json['ph3_varLh_acumm']);
                    $json['varCh_acumm'] = $json['varCh_acumm'] + floatval($last_raw_json['varCh_acumm']);
                    $json['varLh_acumm'] = $json['varLh_acumm'] + floatval($last_raw_json['varLh_acumm']);
                    $json['ph1_varCh_interval'] = $json['ph1_varCh_acumm'] - floatval($reference_data_json['ph1_varCh_acumm']);
                    $json['ph1_varLh_interval'] = $json['ph1_varLh_acumm'] - floatval($reference_data_json['ph1_varLh_acumm']);
                    $json['ph2_varCh_interval'] = $json['ph2_varCh_acumm'] - floatval($reference_data_json['ph2_varCh_acumm']);
                    $json['ph2_varLh_interval'] = $json['ph2_varLh_acumm'] - floatval($reference_data_json['ph2_varLh_acumm']);
                    $json['ph3_varCh_interval'] = $json['ph3_varCh_acumm'] - floatval($reference_data_json['ph3_varCh_acumm']);
                    $json['ph3_varLh_interval'] = $json['ph3_varLh_acumm'] - floatval($reference_data_json['ph3_varLh_acumm']);
                    $json['ph1_kwh_interval'] = $json['ph1_import_kwh'] - $reference_data_json['ph1_import_kwh'];
                    $json['ph2_kwh_interval'] = $json['ph2_import_kwh'] - $reference_data_json['ph2_import_kwh'];
                    $json['ph3_kwh_interval'] = $json['ph3_import_kwh'] - $reference_data_json['ph3_import_kwh'];
                    $json['ph1_varh_interval'] = $json['ph1_import_kvarh'] - $reference_data_json['ph1_import_kvarh'];
                    $json['ph2_varh_interval'] = $json['ph2_import_kvarh'] - $reference_data_json['ph2_import_kvarh'];
                    $json['ph3_varh_interval'] = $json['ph3_import_kvarh'] - $reference_data_json['ph3_import_kvarh'];
                    $json['varCh_interval'] = $json['varCh_acumm'] - floatval($reference_data_json['varCh_acumm']);
                    $json['varLh_interval'] = $json['varLh_acumm'] - floatval($reference_data_json['varLh_acumm']);

                }
            }

            $this->model->client_id = $client->id;
            $this->model->accumulated_real_consumption = floatval($json['import_wh']);
            $this->model->interval_real_consumption = floatval($json['kwh_interval']);
            $this->model->accumulated_reactive_consumption = floatval($json['import_VArh']);
            $this->model->interval_reactive_consumption = floatval($json['varh_interval']);
            $this->model->accumulated_reactive_capacitive_consumption = $json['varCh_acumm'];
            $this->model->accumulated_reactive_inductive_consumption = $json['varLh_acumm'];
            $this->model->interval_reactive_capacitive_consumption = floatval($json['varCh_interval']);
            $this->model->interval_reactive_inductive_consumption = floatval($json['varLh_interval']);
            $this->model->raw_json = $json;
            $this->model->saveQuietly();
            /*if ($this->>model->interval_real_consumption == 0) {
                $penalizable_inductive = $this->>model->interval_reactive_inductive_consumption;
            } else {
                $percent_penalizable_inductive = ($this->>model->interval_reactive_inductive_consumption * 100) / $this->>model->interval_real_consumption;
                if ($percent_penalizable_inductive >= 50) {
                    $penalizable_inductive = ($this->>model->interval_real_consumption * $percent_penalizable_inductive / 100) - ($this->>model->interval_real_consumption * 0.5);
                } else {
                    $penalizable_inductive = 0;
                }
            }
            HourlyMicrocontrollerData::updateOrCreate(
                ['year' => $current_time->format('Y'),
                    'month' => $current_time->format('m'),
                    'day' => $current_time->format('d'),
                    'hour' => $current_time->format('H'),
                    'client_id' => $this->>model->client_id],
                ['microcontroller_data_id' => $this->>model->id,
                    'interval_real_consumption' => $this->>model->interval_real_consumption,
                    'interval_reactive_capacitive_consumption' => $this->>model->interval_reactive_capacitive_consumption,
                    'interval_reactive_inductive_consumption' => $this->>model->interval_reactive_inductive_consumption,
                    'penalizable_reactive_capacitive_consumption' => $this->>model->interval_reactive_capacitive_consumption,
                    'penalizable_reactive_inductive_consumption' => $penalizable_inductive,
                    'source_timestamp' => $this->>model->source_timestamp,
                    'raw_json' => json_encode($this->>model->raw_json),
                ]
            );*/
        } catch (Exception $exception){
            return $exception;
        }


    }
}
