<?php

namespace App\Http\Repositories\ConfigurationClient\Impl;

use App\Http\Repositories\ConfigurationClient\ConfigClientRepository;
use App\Jobs\V1\Api\ConfigurationClient\CheckAckLogJob;
use App\Models\Model\V1\Firmware;
use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use App\ModulesAux\MQTT;
use Carbon\Carbon;
use Crc16\Crc16;
use Illuminate\Support\Facades\Request;
use PhpMqtt\Client\Exceptions\MqttClientException;

class ConfigClientRepositoryImpl implements ConfigClientRepository
{
    public function runService()
    {
        $data_frame_events = config('data-frame.data_frame_events');
        foreach ($data_frame_events as $event) {
            if(array_key_exists('uri_event', $event)) {
                if($event['uri_event'] == Request::header(EventLog::API_EVENT_HEADER)){
                    $serial = Request::query('serial');
                    if($serial == null) {
                        $serial = Request::input('serial');
                    }
                    $data = $this->packMessage($event);
                    $message = $data['message'];
                    $response = $this->getReturnMessage($serial, $message, $data['eventLog'], $event['event_id']);
                    // se completa primer evento de la transaccion
                    $eventLog = EventLog::find($this->getEventLogId());
                    $eventLog->update([
                        "status" => EventLog::STATUS_SUCCESSFUL,
                        "response_json" => json_encode($response)
                    ]);
                    return $response;
                }
            }
        }
    }

    private function packMessage($event)
    {
        $serial = Request::query('serial');
        if($serial == null) {
            $serial = Request::input('serial');
        }
        $ackLog = AckLog::find($this->getAckLogId());
        $request = Request::instance();
        // Crear evento server a mc
        $eventLog = EventLog::createMcEvent($ackLog, $request, EventLog::MAIN_SERVER_MC_REQUEST, null, null);
        $message = '';
        $json_request = [];
        foreach ($event['frame'] as $index => $datum) {
            if ($datum['variable_name'] == 'id_event_log') {
                $value = pack($datum['type'], $eventLog->id);
                $message = $message . $value;
                $json_request[$datum['variable_name']] = $eventLog->id;
            } elseif ($datum['variable_name'] == 'event_id') {
                $value = pack($datum['type'], $event['event_id']);
                $message = $message . $value;
                $json_request[$datum['variable_name']] = $event['event_id'];
            } elseif ($datum['variable_name'] == 'crc') {
                $crc = Crc16::XMODEM($message);
                $value = pack($datum['type'], $crc);
                $message = $message . $value;

            } elseif ($datum['variable_name'] == 'salida_id') {
                $value = pack($datum['type'], 1);
                $message = $message . $value;
                $json_request[$datum['variable_name']] = 1;
            } elseif ($datum['variable_name'] == 'time_sampling_choice') {
                $parameter_value = Request::query($datum['parameter_name']);
                if($parameter_value == 'hourly'){
                    $value = pack($datum['type'], 1);
                    $json_request[$datum['variable_name']] = 1;
                } elseif ($parameter_value == 'hourly') {
                    $value = pack($datum['type'], 2);
                    $json_request[$datum['variable_name']] = 2;
                } else {
                    $value = pack($datum['type'], 3);
                    $json_request[$datum['variable_name']] = 3;
                }
                $message = $message . $value;
            } elseif ($datum['variable_name'] == 'frame_alerts'){
                $alert_config_frame = config('data-frame.alert_config_frame');
                $binary_data = [];
                $related_parameter = Request::json();
                foreach ($alert_config_frame as $item) {
                    if ($item['variable_name'] == 'network_operator_id') {
                        continue;
                    } elseif ($item['variable_name'] == 'equipment_id') {
                        continue;
                    } elseif ($item['variable_name'] == 'network_operator_new_id') {
                        continue;
                    } elseif ($item['variable_name'] == 'equipment_new_id') {
                        continue;
                    } else {
                        $data = $related_parameter->get($item['variable_name']);
                        array_push($binary_data, pack($item['type'], $data));
                    }
                }
                $frame = implode($binary_data);
                $message = $message . $frame;
                $json_request[$datum['variable_name']] = $related_parameter->all();
            } elseif ($datum['variable_name'] == 'frame_control'){
                $alert_config_frame = config('data-frame.alert_config_frame');
                $binary_data = [];
                $related_parameter = Request::json();
                foreach ($alert_config_frame as $item) {
                    if ($item['variable_name'] == 'network_operator_id') {
                        continue;
                    } elseif ($item['variable_name'] == 'equipment_id') {
                        continue;
                    } elseif ($item['variable_name'] == 'network_operator_new_id') {
                        continue;
                    } elseif ($item['variable_name'] == 'equipment_new_id') {
                        continue;
                    } else {
                        $data = $related_parameter->get($item['variable_name']);
                        array_push($binary_data, pack($item['type'], $data));
                    }
                }
                $frame = implode($binary_data);
                $message = $message . $frame;
                $json_request[$datum['variable_name']] = $related_parameter->all();
            } elseif ($datum['variable_name'] == 'frame_status'){
                $alert_config_frame = config('data-frame.alert_config_frame');
                $binary_data = [];
                $related_parameter = Request::json();
                $flag_id = 0;
                foreach ($alert_config_frame as $item) {
                    if ($item['variable_name'] == 'network_operator_id') {
                        continue;
                    } elseif ($item['variable_name'] == 'equipment_id') {
                        continue;
                    } elseif ($item['variable_name'] == 'network_operator_new_id') {
                        continue;
                    } elseif ($item['variable_name'] == 'equipment_new_id') {
                        continue;
                    } else {
                        if ($flag_id != $item['flag_id']) {
                            $data = $related_parameter->get(str_replace(["max_", "min_"], "status_", $item['variable_name']));
                            array_push($binary_data, pack('C', $data));
                            $flag_id = $item['flag_id'];
                        }
                    }
                }
                $frame = implode($binary_data);
                $message = $message . $frame;
                $json_request[$datum['variable_name']] = $related_parameter->all();
            }  elseif ($datum['variable_name'] == 'size_file'){
                $archivoBin = Request::input('version');
                if ($archivoBin !== null) {
                    $firmware = Firmware::find($archivoBin);
                    $filePath = $firmware->downloadFileFromS3($firmware->evidence()->path);
                    $sizeFile=filesize($filePath);
                    $value = pack($datum['type'], $sizeFile);
                    $json_request[$datum['variable_name']] = $sizeFile;
                    $message = $message . $value;
                }
            } else {
                if ($datum['format'] == 'lenght') {
                    $nextIndex = $index + 1;
                    $nextDatum = isset($event['frame'][$nextIndex]) ? $event['frame'][$nextIndex] : null;
                    if ($nextDatum != null) {
                        $related_parameter = Request::query($nextDatum['parameter_name']);
                        $init_value = strlen($related_parameter);
                        $value = pack($datum['type'], $init_value);
                        $json_request[$datum['variable_name']] = $init_value;
                    }
                } elseif ($datum['format'] == 'string') {
                    $value = Request::query($datum['parameter_name']);
                    $json_request[$datum['variable_name']] = $value;
                } elseif ($datum['format'] == 'unix') {
                    // Artisan::call('ntpdate', ['server' => 'pool.ntp.org']);
                    $now = Carbon::now();
                    $init_value = $now->timestamp;
                    $json_request[$datum['variable_name']] = $init_value;
                    $value = pack($datum['type'], $init_value);
                } else {
                    $init_value = Request::query($datum['parameter_name']);
                    if($init_value == null) {
                        $init_value = Request::input($datum['parameter_name']);
                    }
                    $json_request[$datum['variable_name']] = $init_value;
                    $value = pack($datum['type'], $init_value);
                }
                $message = $message . $value;
            }
        }

        //// actualizar evento server a mc con json_request
        $eventLog->request_json = json_encode($json_request);
        $eventLog->save();
        return ['message' => $message, 'eventLog' => $eventLog];
    }

    public function getAckLogId()
    {
        return json_decode(Request::header(AckLog::ACK_LOG_HEADER), true)["id"];
    }

    public function getEventLogId()
    {
        return json_decode(Request::header(EventLog::EVENT_LOG_HEADER), true)["id"];
    }

    public function getReturnMessage($serial, $message, $eventLogMc, $event_id)
    {
        try {
            $this->sendMqttMessage($serial, $message, $eventLogMc, $event_id);
            return $this->makeMessageResponse(serial: $serial, message: "Se realizo el envio del mensaje", detail: "Se espera respuesta del equipo para confirmar la conexión");
        } catch (MqttClientException $e) {
            $statusCode = $e->getCode();
            $errorMessage = $e->getMessage();
            $errorInfo = [
                'status_code' => $statusCode,
                'error_message' => $errorMessage,
            ];
            $eventLog = EventLog::find($eventLogMc->id);
            $eventLog->status = EventLog::STATUS_ERROR;
            $eventLog->response_json = json_encode($errorInfo);
            $eventLog->save();
            $eventLog->ackLog->update([
                "status" => AckLog::STATUS_EXPIRED
            ]);
            return $this->makeMessageResponse(serial: $serial, message: "Falló el envio del mensaje", detail: $errorInfo);
        }
    }

    private function sendMqttMessage($serial, $message, $eventLog, $event_id)
    {
        $topic = "v1/mc/config/" . $serial;
        $mqtt = MQTT::connection("default", $this->getMqttConnectionName());
        $mqtt->publish($topic, $message);
        $mqtt->disconnect();
        dispatch(new CheckAckLogJob($this->getMqttConnectionName(), $eventLog->id, $event_id, $serial))->onQueue('spot2');
    }

    private function getMqttConnectionName()
    {
        return Request::header(EventLog::API_EVENT_HEADER) . "-" . Request::header("serial");
    }

    public function makeMessageResponse($serial, $message, $detail)
    {
        return [
            "serial" => $serial,
            "message" => $message,
            "detail" => $detail,
            "event_id" => $this->getEventLogId(),
            "transaction_id" => $this->getAckLogId(),
        ];
    }

    private function decodeMqttConnectionName($connectionName)
    {
        return [
            "serial" => explode("-", $connectionName)[1],
            "event" => explode("-", $connectionName)[0],
        ];
    }
}
