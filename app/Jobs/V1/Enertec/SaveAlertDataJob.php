<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SaveAlertDataJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $raw_json;
    public $source_timestamp;
    public $is_control;

    public function  __construct($raw_json, $is_control)
    {
        $this->raw_json = $raw_json;
        $this->source_timestamp = new Carbon();
        $this->is_control = $is_control;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->alertVariableEvent();
    }

    private function alertVariableEvent()
    {
        $flags_frame = config('data-frame.flags_frame');
        $decode = bin2hex(base64_decode($this->raw_json));
        $timestamp = (unpack('l', hex2bin(substr($decode, 64, 8)))[1]);
        $date = new Carbon();
        $date->setTimestamp($timestamp);
        $current_time = Carbon::now();

        if($date->diffInYears($current_time) <= 1){
            echo $date->format('Y-m-d H:i:s');
            $flag = $this->calculateValueAlert(5, $decode);
            $binary_flags = sprintf("%064b", ($flag));
            $equipment_serial = str_pad($this->calculateValueAlert(2, $decode), 6, "0", STR_PAD_LEFT);
            $client = Client::getClientFromSerial($equipment_serial);
            if ($client == null) {
                return;
            }
            $timestamp = $this->calculateValueAlert(6, $decode);

            $this->source_timestamp->setTimestamp($timestamp);
            $last_hour = $this->source_timestamp->copy();
            $last_hour->subHour();
            $energy_hour = $client->hourlyMicrocontrollerData()->whereBetween('source_timestamp', [$last_hour->format('Y-m-d H:00:00'), $last_hour->format('Y-m-d H:59:59')])->orderBy('source_timestamp', 'desc')->first();
            $energy_month = $client->monthlyMicrocontrollerData()->orderBy('id', 'desc')->first();


            $value = 0;
            $status_coil = false;
            foreach ($flags_frame as $item) {
                if($item['id'] == 4){
                   foreach ($client->digitalOutputs as $output){
                       $split = substr($binary_flags, $item['index'], 1);
                       $status_coil = $split == 1;
                       $output->status = $status_coil;
                       $output->save();
                   }
                }
                if ($item['id'] >= 16) {
                    $alert = $client->clientAlertConfiguration()->where('flag_id', $item['id'])->first();
                    $type = "";
                    $split = substr($binary_flags, $item['index'], 1);

                    echo $item['id']."\n";
                    if ($split == "1") {
                        if ($item['flag_name'] == 'flagOpened') {
                            $value = 1;
                            $type = ClientAlert::ALERT;
                        } else {
                            echo $item['id']."\n";
                            if ($alert) {
                                if ($item['id'] <= 42){
                                    $value = $this->calculateValueAlert($item['variable_id'], $decode);
                                } else{
                                    $value = $this->calculateValueAlertEnergy($item['id'], $energy_month, $energy_hour, $decode);
                                }
                                echo $value." - ".$split. " - ". $item['id']." - ".$alert->max_control. " - ".$item['variable_id']."\n";

                                if ($this->is_control) {
                                    if ($alert->min_control != 0) {
                                        if ($value < $alert->min_control) {
                                            $type = ClientAlert::CONTROL;
                                        }
                                    }
                                    if ($alert->max_control != 0) {
                                        if ($value > $alert->max_control) {
                                            $type = ClientAlert::CONTROL;
                                        }
                                    }
                                } else {
                                    if ($alert->min_alert != 0) {
                                        if ($value < $alert->min_alert) {
                                            $type = ClientAlert::ALERT;
                                        }
                                    }
                                    if ($alert->max_alert != 0) {
                                        if ($value > $alert->max_alert) {
                                            $type = ClientAlert::ALERT;
                                        }
                                    }
                                }
                            }
                        }
                        if ($alert) {
                            if ($type != "") {
                                //$microcontroller_data = MicrocontrollerData::whereRawJson($this->raw_json)->first();
                                $alertGenerated = ClientAlert::create([
                                    'client_id' => $client->id,
                                    'microcontroller_data_id' => null,
                                    'client_alert_configuration_id' => $alert->id,
                                    'value' => $value,
                                    'type' => $type,
                                    'source_timestamp' => $this->source_timestamp->format('Y-m-d H:i:s'),
                                    'event_log_id' => null,
                                ]);
                                $json = [
                                    "serial" => $equipment_serial,
                                    "timestamp" => $this->source_timestamp->format('Y-m-d H:i:s'),
                                    "variable_name" => $alertGenerated->clientAlertConfiguration->getVariableName(),
                                    "value" => $value,
                                    "max_value" => $this->is_control? $alert->max_control:$alert->max_alert,
                                    "min_value" => $this->is_control? $alert->min_control:$alert->min_alert,
                                    "status_coil" => $status_coil,
                                ];
                                $ackLog = AckLog::create(["serial" => $equipment_serial]);
                                $event_type = $this->is_control ? EventLog::EVENT_ALERT_CONTROL_NOTIFICATION: EventLog::EVENT_ALERT_NOTIFICATION;
                                    $eventLog = EventLog::create([
                                    "name" => $event_type . "_" . EventLog::MAIN_SERVER_MC_REQUEST,
                                    "event" => $event_type,
                                    "client_id" => $client->id,
                                    "request_endpoint" => null,
                                    "request_json" => null,
                                    "response_json" => json_encode($json),
                                    "webhook" => null,
                                    "serial" => $equipment_serial,
                                    "request_type" => EventLog::MAIN_SERVER_MC_REQUEST,
                                    "status" => EventLog::STATUS_SUCCESSFUL,
                                    "ack_log_id" => $ackLog->id
                                ]);
                                $apiKey = ApiKey::first();
                                $webhook = $apiKey->end_point_notification;
                                $eventLogWh = EventLog::create([
                                    "name" => $eventLog->event . "_" . EventLog::MAIN_SERVER_CLIENT_RESPONSE,
                                    "event" => $eventLog->event,
                                    "client_id" => $client->id,
                                    "request_endpoint" => null,
                                    "request_json" => null,
                                    "response_json" => null,
                                    "webhook" => $webhook,
                                    "serial" => $equipment_serial,
                                    "request_type" => EventLog::MAIN_SERVER_CLIENT_RESPONSE,
                                    "status" => EventLog::STATUS_CREATED,
                                    "ack_log_id" => $eventLog ? $eventLog->ack_log_id : null
                                ]);
                                $alertGenerated->event_log_id = $eventLogWh->id;
                                $alertGenerated->save();
                                $jsonMessage = [
                                    ['id' => 1 , 'variable_name'=> 'notification_type_id', 'value' => 24,                                                               'parameter_name' => null,           'object' => []],
                                    ['id' => 2 , 'variable_name'=> 'message',              'value' => 'Alerta de variable fuera de rango', 'parameter_name' => null,           'object' => []],
                                    ['id' => 3 , 'variable_name'=> 'success',              'value' => 1,                                                               'parameter_name' => null,           'object' => []],
                                    ['id' => 4 , 'variable_name'=> 'serial',               'value' => null,                                                            'parameter_name' => 'serial',       'object' => []],
                                    ['id' => 5 , 'variable_name'=> 'id_transaction',       'value' => null,                                                            'parameter_name' => null,           'object' => []],
                                    ['id' => 6 , 'variable_name'=> 'id_event',             'value' => null,                                                            'parameter_name' => null, 'object' => []],
                                    ['id' => 7 , 'variable_name'=> 'data',                 'value' => null,                                                            'parameter_name' => null,           'object' => [
                                        ['variable_name'=> 'response_date',  'parameter_name' => 'timestamp', 'format' => 'number'],
                                        ['variable_name'=> 'variable_name',  'parameter_name' => 'variable_name', 'format' => 'string'],
                                        ['variable_name'=> 'value',  'parameter_name' => 'value', 'format' => 'number'],
                                        ['variable_name'=> 'max_value',  'parameter_name' => 'max_value', 'format' => 'number'],
                                        ['variable_name'=> 'min_value',  'parameter_name' => 'min_value', 'format' => 'number'],
                                        ['variable_name'=> 'status_coil',  'parameter_name' => 'status_coil', 'format' => 'number'],
                                    ]
                                    ],
                                ];
                                foreach ($jsonMessage as $datum) {
                                    if ($datum['value'] != null) {
                                        $jsonResponse[$datum['variable_name']] = $datum['value'];
                                    } elseif ($datum['parameter_name'] != null) {
                                        $jsonResponse[$datum['variable_name']] = array_key_exists($datum['parameter_name'], $json) ? $json[$datum['parameter_name']] : null;
                                    } else {
                                        if ($datum['variable_name'] == 'id_transaction') {
                                            $jsonResponse[$datum['variable_name']] = $eventLogWh ? $eventLogWh->ackLog->id : null;
                                        } elseif ($datum['variable_name'] == 'id_event') {
                                            $jsonResponse[$datum['variable_name']] = $eventLogWh ? $eventLogWh->id : null;
                                        } else {
                                            foreach ($datum['object'] as $property) {
                                                if ($property['format'] == 'date') {
                                                    if (array_key_exists($property['parameter_name'], $json)) {
                                                        $date = Carbon::now();
                                                        $date->setTimestamp($json[$property['parameter_name']]);

                                                        $object[$property['variable_name']] = $date->format('Y-m-d H:i:s');
                                                    } else {
                                                        $object[$property['variable_name']] = null;
                                                    }
                                                } else {
                                                    $object[$property['variable_name']] = array_key_exists($property['parameter_name'], $json) ? $json[$property['parameter_name']] : null;
                                                }
                                            }
                                            $jsonResponse[$datum['variable_name']] = $object;
                                        }
                                    }
                                }
                                if ($jsonResponse != null) {
                                    if ($eventLogWh) {
                                        $eventLogWh->request_json = json_encode($jsonResponse);
                                        $eventLogWh->save();
                                    }
                                    $requestDetails = [
                                        'url' => $webhook,
                                        'method' => 'POST',
                                        // 'headers' => $e->request->headers()->all(),
                                        'body' => $jsonResponse,
                                    ];

                                    try {

                                        $response = Http::withHeaders([
                                            $apiKey->security_header_value => $apiKey->security_header_key,
                                        ])->withoutVerifying()->post($webhook, $jsonResponse);
                                        //$response = Http::post($webhook, $jsonResponse);

                                        $jsonData = $response->json();
                                        if ($eventLogWh) {
                                            $eventLogWh->status = EventLog::STATUS_SUCCESSFUL;
                                            $eventLogWh->response_json = json_encode($jsonData);
                                            $eventLogWh->save();
                                            $ackLog = $eventLogWh->ackLog;
                                            $ackLog->status = AckLog::STATUS_SUCCESS;
                                            $ackLog->save();
                                        }

                                        //dd($jsonData);
                                    } catch (\Throwable $e) {
                                        $statusCode = $e->getCode();
                                        $errorMessage = $e->getMessage();

                                        $errorInfo = [
                                            'status_code' => $statusCode,
                                            'error_message' => $errorMessage,
                                            'response_body' => null,
                                            'request_details' => $requestDetails
                                        ];
                                        if ($eventLogWh) {
                                            $eventLogWh->status = EventLog::STATUS_ERROR;
                                            $eventLogWh->response_json = json_encode($errorInfo);
                                            $eventLogWh->save();
                                            $ackLog = $eventLog->ackLog;
                                            $ackLog->status = AckLog::STATUS_EXPIRED;
                                            $ackLog->save();
                                        }
                                        error_log($e->getMessage());
                                    }
                                } else {
                                    if ($eventLog != null) {
                                        $ackLog = $eventLog->ackLog;
                                        $ackLog->status = AckLog::STATUS_SUCCESS;
                                        $ackLog->save();
                                    }
                                    if ($eventLogWh != null) {
                                        $eventLogWh->delete();
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }

    }

    private function calculateValueAlert($variable_id, $decode)
    {
        $data_frame = collect(config('data-frame.data_frame'));
        $variable = $data_frame->where('id', $variable_id)->first();
        $split = substr($decode, ($variable['start']), ($variable['lenght']));
        $bin = hex2bin($split);
       if ($variable['variable_name'] == "flags") {
            $value = strval(unpack($variable['type'], $bin)[1]);
        } else {
            $value = unpack($variable['type'], $bin)[1];
        }
       echo $variable['id']." - ". $split ." - ".$bin."\n";


        return $value;
    }


    public function calculateValueAlertEnergy($flag_id, $energy_month, $energy_hour, $decode)
    {
        $value = 0;
        if ($energy_month) {
            if ($flag_id == 43) {
                $value = $this->calculateValueAlert(33, $decode) - $energy_month->microcontrollerData->accumulated_real_consumption;
            } elseif ($flag_id == 44) {
                $accumulated_reactive_inductive_consumption = $this->calculateValueAlert(55, $decode) + $this->calculateValueAlert(56, $decode) + $this->calculateValueAlert(57, $decode);
                $value = $accumulated_reactive_inductive_consumption - $energy_month->microcontrollerData->accumulated_reactive_inductive_consumption;
            } elseif ($flag_id == 45) {
                $accumulated_reactive_capacitive_consumption = $this->calculateValueAlert(58, $decode) + $this->calculateValueAlert(59, $decode) + $this->calculateValueAlert(60, $decode);
                $value = $accumulated_reactive_capacitive_consumption - $energy_month->microcontrollerData->accumulated_reactive_capacitive_consumption;
            }
        }
        if ($energy_hour) {
            if ($flag_id == 46) {
                $value = $this->calculateValueAlert(33, $decode) - $energy_hour->microcontrollerData->accumulated_real_consumption;
            } elseif ($flag_id == 47) {
                $accumulated_reactive_inductive_consumption = $this->calculateValueAlert(55, $decode) + $this->calculateValueAlert(56, $decode) + $this->calculateValueAlert(57, $decode);
                $value = $accumulated_reactive_inductive_consumption - $energy_hour->microcontrollerData->accumulated_reactive_inductive_consumption;
            } elseif ($flag_id == 48) {
                $accumulated_reactive_capacitive_consumption = $this->calculateValueAlert(58, $decode) + $this->calculateValueAlert(59, $decode) + $this->calculateValueAlert(60, $decode);
                $value = $accumulated_reactive_capacitive_consumption - $energy_hour->microcontrollerData->accumulated_reactive_capacitive_consumption;
            }
            if ($flag_id == 49) {
                $interval_real_consumption = $this->calculateValueAlert(33, $decode) - $energy_hour->microcontrollerData->accumulated_real_consumption;
                if ($interval_real_consumption != 0) {
                    $accumulated_reactive_inductive_consumption = $this->calculateValueAlert(55, $decode) + $this->calculateValueAlert(56, $decode) + $this->calculateValueAlert(57, $decode);
                    $interval_reactive_inductive_consumption = $accumulated_reactive_inductive_consumption - $energy_hour->microcontrollerData->accumulated_reactive_inductive_consumption;
                    $value = ($interval_reactive_inductive_consumption * 100) / $interval_real_consumption;
                    if ($value > 200){
                        $value = 0;
                    }
                } else {
                    $value = 0;
                }
            }
            if ($flag_id == 50) {
                $interval_real_consumption = $this->calculateValueAlert(33, $decode) - $energy_hour->microcontrollerData->accumulated_real_consumption;
                if ($interval_real_consumption != 0) {
                    $accumulated_reactive_capacitiva_consumption = $this->calculateValueAlert(58, $decode) + $this->calculateValueAlert(59, $decode) + $this->calculateValueAlert(60, $decode);
                    $interval_reactive_capacitive_consumption = $accumulated_reactive_capacitiva_consumption - $energy_hour->microcontrollerData->accumulated_reactive_capacitive_consumption;
                    $value = ($interval_reactive_capacitive_consumption * 100) / $interval_real_consumption;
                } else {
                    $value = 0;
                }
            }
        }


        return $value;
    }
}
