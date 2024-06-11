<?php

namespace App\Jobs\V1\Api\ConfigurationClient;


use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Api\EventLog;
use Carbon\Carbon;
use Crc16\Crc16;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
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
            foreach ($data_frame_events as $index => $event) {
                if ($event['event_id'] ==  $event_id) {
                    foreach ($event['frame'] as $datum) {

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
                                $data_crc = substr($message, 0, -2);
                                $json[$datum['variable_name']] = unpack($datum['type'], $data_crc)[1];
                            }else{
                                $value = unpack($datum['type'], $split)[1];
                                $json[$datum['variable_name']] = $value;
                            }
                        }
                    }
                        echo $event_id . " - " . $json['serial'];
                    break;
                }
            }
        } else {
            return;
        }
        $serial = $json['serial'];
        $client = Client::getClientFromSerial($serial);
        if ($event_id == 32){
            $apiKey =ApiKey::first();
            $response = Http::withHeaders([
                'x-api-key' => $apiKey->api_key,
            ])->withoutVerifying()->get('https://aom.enerteclatam.com/api/v1/config/set-date', [
                'serial' => $json['serial'],
            ]);
        }
        if ($client == null) {
            $equipment_type = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();
            $equipment = Equipment::create([
                'equipment_type_id' => $equipment_type->id,
                'serial' => $serial,
                'name' => 'MEDIDOR',
                'description' => 'Medidor enelar',
                'admin_id' => 1,
                'network_operator_id' => 7,
                'has_admin' => true,
                'has_network_operator' => true
            ]);
            $apiKey =ApiKey::first();
            $response = Http::withHeaders([
                'x-api-key' => $apiKey->api_key,
            ])->withoutVerifying()->post('https://aom.enerteclatam.com/api/v1/clients/client-add', [
                'serial' => $equipment->serial,
            ]);
            return;
        }

        if (array_key_exists('job_name', $event)) {
            $jobInstance = "App\\Jobs\\V1\\Api\\ConfigurationClient\\{$event['job_name']}";
            if (class_exists($jobInstance)) {
                if (class_exists("App\\Jobs\\V1\\Api\\ConfigurationClient\\{$event['job_name']}")) {
                    if($event_id == 43){
                        dispatch(new $jobInstance($json,0,1))->onQueue('spot3');
                    } else{
                        dispatch(new $jobInstance($json))->onQueue('spot3');
                    }
                }
            }
        }
        $eventLog = null;
        $eventLogWh = null;
        $apiKey = ApiKey::first();
        $webhook = $apiKey->end_point_notification;

        if ($json != null) {
            $data_webhook_events = config('data-frame.webhook_events');
            $jsonResponse = null;
            if (array_key_exists('id_event_log', $json)) {
                if ($json['event_id'] == 44){
                    $eventLog_last = EventLog::find($json['id_event_log']);
                    echo $eventLog_last->event;
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
                        "ack_log_id" => $eventLog_last->ackLog->id
                    ]);
                } else{
                    $eventLog = EventLog::find($json['id_event_log']);
                    if ($client->id == $eventLog->client_id) {
                        $eventLog->update([
                            "status" => EventLog::STATUS_SUCCESSFUL,
                            "response_json" => json_encode($json)
                        ]);
                    }
                }
            } else{
                if (array_key_exists('uri_event', $event)) {

                    $ackLog = AckLog::create(["serial" => $serial]);
                    $eventLog = EventLog::create([
                        "name" => $event['uri_event'] . "_" . EventLog::MAIN_SERVER_MC_REQUEST,
                        "event" => $event['uri_event'],
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

            foreach ($data_webhook_events as $event) {
                if ($event['event_id'] == $event_id) {
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
                        "ack_log_id" => $eventLog ? $eventLog->ack_log_id : null
                    ]);
                    foreach ($event['json'] as $datum) {
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

                    $jsonData = $response->json();
                    if ($eventLogWh) {
                        $eventLogWh->status = EventLog::STATUS_SUCCESSFUL;
                        $eventLogWh->response_json = json_encode($jsonData);
                        $eventLogWh->save();
                        $ackLog = $eventLogWh->ackLog;
                        $ackLog->status = AckLog::STATUS_SUCCESS;
                        $ackLog->save();
                    }

                    dump($jsonData);
                } catch (\Throwable $e) {
                    $statusCode = $e->getCode();
                    $errorMessage = $e->getMessage();
                    $responseBody = null;
                    $errorInfo = [
                        'status_code' => $statusCode,
                        'error_message' => $errorMessage,
                        'response_body' => $responseBody,
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
                  //  error_log($e->getMessage());
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
