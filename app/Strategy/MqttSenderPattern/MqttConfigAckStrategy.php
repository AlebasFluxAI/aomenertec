<?php

namespace App\Strategy\MqttSenderPattern;

use App\Models\V1\EquipmentType;
use Crc16\Crc16;
use PhpMqtt\Client\MqttClient;

class MqttConfigAckStrategy implements MqttSenderInterface
{
    use MqttSenderTrait;

    public const EVENT = "config_ack";

    public function setTopic()
    {
        $equipment = $this->component->client->equipments()->whereEquipmentTypeId(7)->first();
        $this->topic = "v1/mc/config/" . $equipment->serial;
        return $this->topic;
    }

    public function setMessage()
    {
        $alert_config_frame = config('data-frame.alert_config_frame');
        $equipment = $this->component->client->equipments()->whereEquipmentTypeId(7)->first();;
        $binary_data = [];
        $data = "";
        foreach ($alert_config_frame as $item) {
            if ($item['variable_name'] == 'network_operator_id') {
                $data = $this->component->client->networkOperator->identification;
            } elseif ($item['variable_name'] == 'equipment_id') {
                $data = $equipment->serial;
            } elseif ($item['variable_name'] == 'network_operator_new_id') {
                $data = $this->component->client->networkOperator->identification;
            } elseif ($item['variable_name'] == 'equipment_new_id') {
                $data = $equipment->serial;
            } else {
                $aux_variable = $this->component->client_config_alert->where('flag_id', $item['flag_id'])->first();
                $data = $aux_variable->{$item['limit']};
            }
            array_push($binary_data, pack($item['type'], $data));
        }
        $event_id = pack('C', 1);
        $eventLogId = pack('V', 888);
        $message = $event_id.$eventLogId.implode($binary_data);
        $crc = Crc16::XMODEM($message);
        $value = pack('v', $crc);
        $this->message = $message . $value;
    }


    public function registerLoopEventHandlerContext(float $elapsedTime, MqttClient $mqtt)
    {
        if ($elapsedTime >= 10) {
            $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Fallo la conexión"]);
            $mqtt->interrupt();
        }
    }


    public function subscribeContext($message_received)
    {
        $message = $message_received;
        $data_frame_events = config('data-frame.data_frame_events');
        $crc_message = substr($message, -2);
        $data_crc = substr($message, 0, -2);
        $crc = Crc16::XMODEM($data_crc);
        $crc_pack = pack('v', $crc);
        $json = null;
        dd($data_frame_events);
        if ($crc_pack == $crc_message) {
            $event_id = unpack('C', $message[0])[1];
            foreach ($data_frame_events as $event) {
                if ($event['event_id'] == 2) {
                    dd($event);
                    foreach ($event['frame'] as $datum) {
                        $split = substr($message, ($datum['start']), ($datum['lenght']));
                        $value = unpack($datum['type'], $split)[1];
                        $json[$datum['variable_name']] = $value;

                    }

                   if ($event_id == 1){
                        dd($json);
                    }
                    break;
                }
            }
        }
            $equipment = EquipmentType::find(1)->equipment()->whereSerial($json['serial'])
                ->first();
            if ($equipment) {
                $client_aux = $equipment->clients()->first();
                if ($client_aux->id == $this->component->client->id) {
                        foreach ($this->component->client_config_alert as $index => $item) {
                            if ($index == "client_notification_type") {
                                continue;
                            }
                            $item->save();
                        }
                        $this->component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Datos actualizados"]);
                        $this->mqtt->interrupt();

                }
            }

    }
}
