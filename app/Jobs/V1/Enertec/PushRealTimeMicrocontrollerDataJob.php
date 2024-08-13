<?php

namespace App\Jobs\V1\Enertec;

use App\Events\RealTimeMonitoringEvent;
use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class PushRealTimeMicrocontrollerDataJob implements ShouldQueue
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
    private $raw_json;

    public function __construct($raw_json)
    {
        $this->raw_json = $raw_json;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->unpackData();
        $jsonResponse = null;
        if ($data) {
            event(new RealTimeMonitoringEvent($data));
            $client = Client::getClientFromSerial($data['equipment_id']);
            $ackLog = AckLog::create(["serial" => $data['equipment_id']]);
            $eventLog = EventLog::create([
                "name" => EventLog::EVENT_REAL_TIME_FRAME . "_" . EventLog::MAIN_SERVER_MC_REQUEST,
                "event" => EventLog::EVENT_REAL_TIME_FRAME,
                "client_id" => $client->id,
                "request_endpoint" => null,
                "request_json" => null,
                "response_json" => json_encode($data),
                "webhook" => null,
                "serial" => $data['equipment_id'],
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
                "serial" => $data['equipment_id'],
                "request_type" => EventLog::MAIN_SERVER_CLIENT_RESPONSE,
                "status" => EventLog::STATUS_CREATED,
                "ack_log_id" => $eventLog ? $eventLog->ack_log_id : null
            ]);
            $data_webhook_events = config('data-frame.webhook_events');
            foreach ($data_webhook_events as $event) {
                if ($event['notification_type_id'] == 25) {
                    foreach ($event['json'] as $datum) {
                        if ($datum['value'] != null) {
                            $jsonResponse[$datum['variable_name']] = $datum['value'];
                        } elseif ($datum['parameter_name'] != null) {
                            $jsonResponse[$datum['variable_name']] = array_key_exists($datum['parameter_name'], $data) ? $data[$datum['parameter_name']] : null;
                        } else {
                            if ($datum['variable_name'] == 'id_transaction') {
                                $jsonResponse[$datum['variable_name']] = $eventLogWh ? $eventLogWh->ackLog->id : null;
                            } elseif ($datum['variable_name'] == 'id_event') {
                                $jsonResponse[$datum['variable_name']] = $eventLogWh ? $eventLogWh->id : null;
                            } else {
                                foreach ($datum['object'] as $property) {
                                    if ($property['format'] == 'date') {
                                        if (array_key_exists($property['parameter_name'], $data)) {
                                            $date = Carbon::now();
                                            $date->setTimestamp($data[$property['parameter_name']]);

                                            $object[$property['variable_name']] = $date->format('Y-m-d H:i:s');
                                        } else {
                                            $object[$property['variable_name']] = null;
                                        }
                                    } else {
                                        $object[$property['variable_name']] = array_key_exists($property['parameter_name'], $data) ? $data[$property['parameter_name']] : null;
                                    }
                                }
                                $jsonResponse[$datum['variable_name']] = $object;
                            }
                        }
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

    private function unpackData()
    {
        $data_frame = config('data-frame.data_frame');
        $decode = bin2hex(base64_decode($this->raw_json));
        $split = substr($decode, (16), (16));
        $bin = hex2bin($split);
        $equipment_serial = str_pad(unpack('Q', $bin)[1], 6, "0", STR_PAD_LEFT);
        $equipment = EquipmentType::find(7)->equipment()->whereSerial($equipment_serial)
            ->first();
        foreach ($data_frame as $data) {
            try {
                $split = substr($decode, ($data['start']), ($data['lenght']));
                if (!$split) {
                    $json[$data['variable_name']] = 0;
                } else {
                    $bin = hex2bin($split);

                    if ($data['variable_name'] == "flags") {
                        $json[$data['variable_name']] = 0;
                    } else {
                        if ($data['variable_name'] == "equipment_id") {
                            $json[$data['variable_name']] = $equipment_serial;
                        } else {
                            $json[$data['variable_name']] = unpack($data['type'], $bin)[1];
                        }
                    }

                }


                if (is_nan($json[$data['variable_name']])) {
                    $json[$data['variable_name']] = null;

                }
                if ($data['variable_name'] == "volt_dc") {
                    break;
                }
            } catch (Exception $e) {
                echo 'Excepción capturada: ', $e->getMessage(), "\n";
            }
        }

        if ($equipment) {
            $client = $equipment->clients()->first();
            $current_time = (new Carbon('now', $client->time_zone));
            $current_time->setTimestamp($json['timestamp']);
            $client_id = $client->id;
            $json['timestamp'] = $current_time->format('Y-m-d H:i:s');
            $json['client_id'] = $client_id;
            return $json;
        }
        return false;
    }
}
