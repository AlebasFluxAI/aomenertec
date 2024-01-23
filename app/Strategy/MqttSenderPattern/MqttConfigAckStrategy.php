<?php

namespace App\Strategy\MqttSenderPattern;

use App\Models\V1\EquipmentType;
use Livewire\Component;
use PhpMqtt\Client\MqttClient;

class MqttConfigAckStrategy implements MqttSenderInterface
{
    use MqttSenderTrait;

    public const EVENT = "config_ack";

    private $topic = 'mc/ack';
    private $mqtt;
    private $message;
    private $component;

    public function __construct(MqttClient $mqtt, Component $component)
    {
        $this->mqtt = $mqtt;
        $this->component = $component;
    }

    public function setTopic()
    {
        $equipment = $this->component->client->equipments()->whereEquipmentTypeId(1)->first();
        $this->topic = "mc/config/" . $equipment->serial;
        return $this->topic;
    }

    public function setMessage()
    {
        $alert_config_frame = config('data-frame.alert_config_frame');
        $equipment = $this->component->client->equipments()->whereEquipmentTypeId(1)->first();;
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
        $this->message = base64_encode(implode($binary_data));
    }


    public function publish()
    {
        $this->mqtt->publish($this->topic, $this->message);
    }

    public function registerLoopEventHandlerContext(float $elapsedTime, MqttClient $mqtt)
    {
        if ($elapsedTime >= 50) {
            $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Fallo la conexión"]);
            $mqtt->interrupt();
        }
    }


    public function subscribeContext($message)
    {
        $json = json_decode($message, true);
        if (array_key_exists(self::EVENT, $json)) {
            $equipment_serial = str_pad($json['did'], 6, "0", STR_PAD_LEFT);
            $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                ->first();
            if ($equipment) {
                $client_aux = $equipment->clients()->first();
                if ($client_aux->id == $this->component->client->id) {
                    if ($json[self::EVENT]) {
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
    }
}
