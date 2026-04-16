<?php

namespace App\Jobs\V1\Api\ConfigurationClient;


use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Api\EventLog;
use Carbon\Carbon;
use Crc16\Crc16;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class SetConfigJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $rawMessage;

    public $tries = 3;

    public function __construct($rawMessage)
    {
        $this->rawMessage = $rawMessage;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message = hex2bin($this->rawMessage);
        $data_frame_events = config('data-frame.data_frame_events');
        $crc_message = substr($message, -2);
        $data_crc = substr($message, 0, -2);
        $crc = Crc16::XMODEM($data_crc);
        $crc_pack = pack('v', $crc);
        $json = null;
        if ($crc_pack == $crc_message) {
            $event_id = unpack('C', $message[0])[1];
            $matchedEvent = null;
            foreach ($data_frame_events as $index => $eventDef) {
                if ($eventDef['event_id'] ==  $event_id) {
                    $matchedEvent = $eventDef;
                    $length = 0;
                    foreach ($eventDef['frame'] as $datum) {

                        $split = substr($message, ($datum['start']), ($datum['lenght']));
                        if ($datum['format'] == 'lenght') {
                            $value = unpack($datum['type'], $split)[1];
                            $json[$datum['variable_name']] = $value;
                            $length = $value;
                        }elseif ($datum['format'] == 'string') {
                            $value = substr($message, ($datum['start']), $length);
                            $json[$datum['variable_name']] = $value;
                        } else{
                            if ($datum['variable_name'] == 'crc'){
                                // Extraer CRC correctamente: últimos 2 bytes del mensaje
                                $json[$datum['variable_name']] = unpack($datum['type'], $crc_message)[1];
                            }else{
                                $value = unpack($datum['type'], $split)[1];
                                $json[$datum['variable_name']] = $value;
                            }
                        }
                    }
                    Log::debug("[ACK] event_id={$event_id} serial=" . ($json['serial'] ?? 'N/A'));
                    break;
                }
            }
        } else {
            Log::warning("[ACK] CRC mismatch - raw hex: " . substr($this->rawMessage, 0, 20) . "...");
            return;
        }

        if ($json === null || $matchedEvent === null) {
            Log::warning("[ACK] No matching frame for event_id or empty json");
            return;
        }

        $serial = $json['serial'] ?? null;
        if ($serial === null) {
            Log::warning("[ACK] Event {$event_id} has no serial field in frame");
            return;
        }

        $client = Client::getClientFromSerial($serial);
        if ($event_id == 32){
            $apiKey = ApiKey::first();
            if ($apiKey) {
                $response = Http::withHeaders([
                    'x-api-key' => $apiKey->api_key,
                ])->withoutVerifying()->get(config('aom.api_url') . config('aom.api_config_path') . '/set-date', [
                    'serial' => $json['serial'],
                ]);
            }
        }
        if ($client == null) {
            $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
            $network_operator = NetworkOperator::first();
            $admin = $network_operator ? $network_operator->admin : null;

            if (!$equipment_type || !$network_operator || !$admin) {
                logger()->warning('SetConfigJob: No se pudo auto-provisionar equipo — faltan datos base', [
                    'serial' => $serial,
                    'equipment_type' => $equipment_type ? $equipment_type->id : null,
                    'network_operator' => $network_operator ? $network_operator->id : null,
                    'admin' => $admin ? $admin->id : null,
                ]);
                return;
            }

            $equipment = Equipment::firstOrCreate(
                ['serial' => $serial],
                [
                    'equipment_type_id' => $equipment_type->id,
                    'name' => 'MEDIDOR',
                    'description' => 'Auto-provisionado',
                    'admin_id' => $admin->id,
                    'network_operator_id' => $network_operator->id,
                    'has_admin' => true,
                    'has_network_operator' => true
                ]
            );

            if ($equipment->wasRecentlyCreated) {
                $apiKey = ApiKey::first();
                if ($apiKey) {
                    Http::withHeaders([
                        'x-api-key' => $apiKey->api_key,
                    ])->withoutVerifying()->post(config('aom.api_url') . config('aom.api_clients_path') . '/client-add', [
                        'serial' => $equipment->serial,
                    ]);
                }
            }
            return;
        }

        if (array_key_exists('job_name', $matchedEvent)) {
            $jobInstance = "App\\Jobs\\V1\\Api\\ConfigurationClient\\{$matchedEvent['job_name']}";
            if (class_exists($jobInstance)) {
                if($event_id == 43){
                    dispatch(new $jobInstance($json,0,1))->onQueue('spot3');
                } else{
                    dispatch(new $jobInstance($json))->onQueue('spot3');
                }
            }
        }
        $eventLog = null;
        $eventLogWh = null;
        $apiKey = ApiKey::first();
        $webhook = $apiKey ? $apiKey->end_point_notification : null;

        if ($json != null) {
            $data_webhook_events = config('data-frame.webhook_events');
            $jsonResponse = null;
            if (array_key_exists('id_event_log', $json)) {
                if ($json['event_id'] == 44){
                    $eventLog_last = EventLog::find($json['id_event_log']);
                    if ($eventLog_last) {
                        $eventLog = EventLog::create([
                            "name" => $eventLog_last->event . "_" . EventLog::MAIN_SERVER_MC_REQUEST,
                            "event" => $eventLog_last->event,
                            "client_id" => $client->id,
                            "request_endpoint" => null,
                            "request_json" => null,
                            "response_json" => json_encode($json),
                            "webhook" => null,
                            "serial" => $serial,
                            "request_type" => EventLog::MAIN_SERVER_MC_REQUEST,
                            "status" => EventLog::STATUS_SUCCESSFUL,
                            "ack_log_id" => $eventLog_last->ackLog ? $eventLog_last->ackLog->id : null
                        ]);
                    }
                } else{
                    $eventLog = EventLog::find($json['id_event_log']);
                    if ($eventLog && $client->id == $eventLog->client_id) {
                        $eventLog->update([
                            "status" => EventLog::STATUS_SUCCESSFUL,
                            "response_json" => json_encode($json)
                        ]);
                    }
                }
            } else{
                if (array_key_exists('uri_event', $matchedEvent)) {

                    $ackLog = AckLog::create(["serial" => $serial]);
                    $eventLog = EventLog::create([
                        "name" => $matchedEvent['uri_event'] . "_" . EventLog::MAIN_SERVER_MC_REQUEST,
                        "event" => $matchedEvent['uri_event'],
                        "client_id" => $client->id,
                        "request_endpoint" => null,
                        "request_json" => null,
                        "response_json" => json_encode($json),
                        "webhook" => null,
                        "serial" => $serial,
                        "request_type" => EventLog::MAIN_SERVER_MC_REQUEST,
                        "status" => EventLog::STATUS_SUCCESSFUL,
                        "ack_log_id" => $ackLog->id
                    ]);
                }
            }

            foreach ($data_webhook_events as $webhookEvent) {
                if ($webhookEvent['event_id'] == $event_id) {
                    if ($eventLog === null) {
                        Log::warning("[ACK] No eventLog for webhook event_id={$event_id}");
                        break;
                    }
                    $eventLogWh = EventLog::create([
                        "name" => $eventLog->event . "_" . EventLog::MAIN_SERVER_CLIENT_RESPONSE,
                        "event" => $eventLog->event,
                        "client_id" => $client->id,
                        "request_endpoint" => null,
                        "request_json" => null,
                        "response_json" => null,
                        "webhook" => $webhook,
                        "serial" => $serial,
                        "request_type" => EventLog::MAIN_SERVER_CLIENT_RESPONSE,
                        "status" => EventLog::STATUS_CREATED,
                        "ack_log_id" => $eventLog->ack_log_id ?? null
                    ]);
                    $object = [];
                    foreach ($webhookEvent['json'] as $datum) {
                        if ($datum['value'] != null) {
                            $jsonResponse[$datum['variable_name']] = $datum['value'];
                        } elseif ($datum['parameter_name'] != null) {
                            $jsonResponse[$datum['variable_name']] = array_key_exists($datum['parameter_name'], $json) ? $json[$datum['parameter_name']] : null;
                        } else {
                            if ($datum['variable_name'] == 'id_transaction') {
                                $jsonResponse[$datum['variable_name']] = $eventLogWh && $eventLogWh->ackLog ? $eventLogWh->ackLog->id : null;
                            } elseif ($datum['variable_name'] == 'id_event') {
                                $jsonResponse[$datum['variable_name']] = $eventLogWh ? $eventLogWh->id : null;
                            } else {
                                $object = [];
                                foreach ($datum['object'] as $property) {
                                    if ($property['format'] == 'date') {
                                        $date = Carbon::now();
                                        if (array_key_exists($property['parameter_name'], $json)) {
                                            $date->setTimestamp($json[$property['parameter_name']]);
                                        }
                                        $object[$property['variable_name']] = $date->format('Y-m-d H:i:s');
                                    } else {
                                        $object[$property['variable_name']] = array_key_exists($property['parameter_name'], $json) ? $json[$property['parameter_name']] : null;
                                    }
                                }
                                $jsonResponse[$datum['variable_name']] = $object;
                            }
                        }
                    }
                    break;
                }
            }
            if ($jsonResponse != null && $webhook != null) {
                if ($eventLogWh) {
                    $eventLogWh->request_json = json_encode($jsonResponse);
                    $eventLogWh->save();
                }
                $requestDetails = [
                    'url' => $webhook,
                    'method' => 'POST',
                    'body' => $jsonResponse,
                ];

                if (!empty($webhook) && $apiKey) {
                    try {

                        $response = Http::withHeaders([
                            $apiKey->security_header_key => $apiKey->security_header_value,
                        ])->withoutVerifying()->post($webhook, $jsonResponse);

                        $jsonData = $response->json();
                        if ($eventLogWh) {
                            $eventLogWh->status = EventLog::STATUS_SUCCESSFUL;
                            $eventLogWh->response_json = json_encode($jsonData);
                            $eventLogWh->save();
                            $ackLog = $eventLogWh->ackLog;
                            if ($ackLog) {
                                $ackLog->status = AckLog::STATUS_SUCCESS;
                                $ackLog->save();
                            }
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
                            $ackLog = $eventLogWh->ackLog;
                            if ($ackLog) {
                                $ackLog->status = AckLog::STATUS_EXPIRED;
                                $ackLog->save();
                            }
                        }
                        Log::error("[ACK] Webhook failed for event_id={$event_id}: " . $e->getMessage());
                    }
                }
            } else {
                if ($eventLog != null && $eventLog->ackLog) {
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
