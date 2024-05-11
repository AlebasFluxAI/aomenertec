<?php

namespace App\Jobs\V1\Api\ConfigurationClient;

use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Api\ApiKey;
use App\ModulesAux\MQTT;
use Crc16\Crc16;
use Illuminate\Support\Facades\Http;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\MqttClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
class CheckAckLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $mqttNameConnection;
    public $eventLogHeaderId;
    public $eventId;
    public $serial;

    public function __construct($mqttNameConnection, $eventLogHeaderId, $eventId, $serial)
    {
        $this->mqttNameConnection = $mqttNameConnection;
        $this->eventLogHeaderId = $eventLogHeaderId;
        $this->eventId = $eventId;
        $this->serial = $serial;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $mqtt = MQTT::connection("default", $this->mqttNameConnection);
            $mqtt->subscribe('v1/mc/ack', function (string $topic, string $message) use ($mqtt) {
                $data_frame_events = config('data-frame.data_frame_events');
                $crc_message = substr($message, -2);
                $data_crc = substr($message, 0, -2);
                $crc = Crc16::XMODEM($data_crc);
                $crc_pack = pack('v', $crc);
                if ($crc_pack == $crc_message) {
                    $event_id = unpack('C', $message[0])[1];
                    foreach ($data_frame_events as $event) {
                        if ($event['event_id'] == $event_id) {
                            foreach ($event['frame'] as $datum) {
                                if ($datum['variable_name'] == 'id_event_log') {
                                    $split = substr($message, ($datum['start']), ($datum['lenght']));
                                    $value = unpack($datum['type'], $split)[1];
                                    if ($value == $this->eventLogHeaderId) {
                                        $mqtt->interrupt();
                                    }
                                }
                            }
                            break;
                        }
                    }
                }
            }, 0);
            $mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) use (&$error) {
                if ($elapsedTime >= 10) {
                    $error = true;
                    try {
                        $eventLog = EventLog::find($this->eventLogHeaderId);
                        if ($eventLog->status == EventLog::STATUS_SUCCESSFUL) {
                            $error = false;
                            $mqtt->interrupt();
                        }
                        if ($error) {
                            $eventLog->update([
                                "status" => EventLog::STATUS_ERROR
                            ]);
                            $eventLog->ackLog->update([
                                "status" => AckLog::STATUS_EXPIRED
                            ]);
                            $this->sendErrorWebhook();
                        }

                        $mqtt->interrupt();
                    } catch (\Throwable $e) {
                        $mqtt->interrupt();
                    }
                }
            });
            $mqtt->loop(true);
            $mqtt->unregisterLoopEventHandler();
            $mqtt->disconnect();
        } catch (MqttClientException $e) {

        }
    }

    public function sendErrorWebhook()
    {
        $eventLog = EventLog::find($this->eventLogHeaderId);
        $data_webhook_events = config('data-frame.webhook_events');
        $jsonResponse = null;
        $apiKey = ApiKey::first();
        $webhook = $apiKey->end_point_notification;
        $eventLogWh = EventLog::create([
            "name" => $eventLog->event . "_" . EventLog::MAIN_SERVER_CLIENT_RESPONSE,
            "event" => $eventLog->event,
            "client_id" => $eventLog->client_id,
            "request_endpoint" => null,
            "request_json" => null,
            "response_json" => null,
            "webhook" => $webhook,
            "request_type" => EventLog::MAIN_SERVER_CLIENT_RESPONSE,
            "status" => EventLog::STATUS_CREATED,
            "ack_log_id" => $eventLog ? $eventLog->ack_log_id : null,
            "serial" => $eventLog ? $eventLog->serial : null
        ]);
        foreach ($data_webhook_events as $event) {
            if ($event['event_id'] == ($this->eventId == 34?41:($this->eventId + 1))) {
                foreach ($event['json'] as $datum) {
                    if ($datum['value'] != null) {
                        if ($datum['variable_name'] == 'success') {
                            $jsonResponse[$datum['variable_name']] = 0;
                        } elseif ($datum['variable_name'] == 'message') {
                            $jsonResponse[$datum['variable_name']] = 'Falló la conexión con el equipo';
                        } else {
                            $jsonResponse[$datum['variable_name']] = $datum['value'];
                        }
                    } elseif ($datum['parameter_name'] != null) {
                        if ($datum['variable_name'] == 'serial') {
                            $jsonResponse[$datum['variable_name']] = $this->serial;
                        }
                    } else {
                        if ($datum['variable_name'] == 'id_transaction') {
                            $jsonResponse[$datum['variable_name']] = $eventLogWh ? $eventLogWh->ackLog->id : null;
                        } elseif ($datum['variable_name'] == 'id_event') {
                            $jsonResponse[$datum['variable_name']] = $eventLogWh ? $eventLogWh->id : null;
                        } else {
                            foreach ($datum['object'] as $property) {
                                $object[$property['variable_name']] = null;
                            }
                            $jsonResponse[$datum['variable_name']] = $object;
                        }
                    }
                }
                break;
            }
        }
        if ($jsonResponse != null) {
            $eventLogWh->request_json = json_encode($jsonResponse);
            $eventLogWh->save();
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
                $jsonData = $response->json();
                $eventLogWh->status = EventLog::STATUS_SUCCESSFUL;
                $eventLogWh->response_json = $jsonData == null ? $jsonData :json_encode($jsonData);
                $eventLogWh->save();
                $ackLog = $eventLogWh->ackLog;
                $ackLog->status = AckLog::STATUS_SUCCESS;
                $ackLog->save();
                //dump($jsonData);

            } catch (\Throwable $e) {
                $statusCode = $e->getCode();
                $errorMessage = $e->getMessage();
                if (property_exists($e, 'response') && $e->response) {
                    $responseBody = $e->response->body(); // Obtener el cuerpo de la respuesta
                } else {
                    $responseBody = null;
                }
                $errorInfo = [
                    'status_code' => $statusCode,
                    'error_message' => $errorMessage,
                    'response_body' => $responseBody,
                    'request_details' => $requestDetails
                ];
                $eventLogWh->status = EventLog::STATUS_ERROR;
                $eventLogWh->response_json = json_encode($errorInfo);
                $eventLogWh->save();
                $ackLog = $eventLog->ackLog;
                $ackLog->status = AckLog::STATUS_EXPIRED;
                $ackLog->save();
                error_log($e->getMessage());
            }
        } else {
            if ($eventLog != null) {
                $ackLog = $eventLog->ackLog;
                $ackLog->status = AckLog::STATUS_SUCCESS;
                $ackLog->save();
            }
            if ($eventLogWh != null) {
                $eventLogWh->forceDelete();
            }
        }
    }
}
