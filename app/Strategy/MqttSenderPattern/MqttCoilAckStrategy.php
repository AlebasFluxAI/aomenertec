<?php

namespace App\Strategy\MqttSenderPattern;

use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\EquipmentType;
use Livewire\Component;
use PhpMqtt\Client\MqttClient;

class MqttCoilAckStrategy implements MqttSenderInterface
{
    use MqttSenderTrait;

    public const EVENT = "coil_ack";

    private $topic = 'mc/ack';
    private $mqtt;
    private $message;
    private $component;
    private $index;
    private $client;

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
        if ($this->component->coils[$this->index]['status']) {
            $message = "{\"coil" . $this->component->coils[$this->index]['number'] . "\":false}";
        } else {
            $message = "{\"coil" . $this->component->coils[$this->index]['number'] . "\":true}";
        }
        $this->message = $message;
    }


    public function publish()
    {
        $this->mqtt->publish($this->topic, $this->message);

    }

    public function registerLoopEventHandlerContext(float $elapsedTime, MqttClient $mqtt)
    {
        if ($elapsedTime >= 20) {
            $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Fallo la conexion"]);
            $mqtt->interrupt();
            $this->component->emit('changeCheck', ['index' => $this->index, 'flag' => false]);
        }
    }

    public function setIndex($index)
    {
        $this->index = $index;
    }

    public function subscribeContext($message)
    {
        $json = json_decode($message, true);
        if (array_key_exists('coil_ack', $json)) {
            $equipment_serial = str_pad($json['did'], 6, "0", STR_PAD_LEFT);
            $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
                ->first();
            if ($equipment) {
                $client = $equipment->clients()->first();
                if ($client->id == $this->client->id) {
                    if ($json['coil_ack']) {
                        $this->component->coils[$this->index]['status'] = !$this->component->coils[$this->index]['status'];
                        $this->component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Accion realizada"]);
                        $coil = ClientDigitalOutput::find($this->component->coils[$this->index]['id']);
                        $this->component->emit('changeCheck', ['index' => $coil->id, 'flag' => true]);
                        $coil->status = $this->component->coils[$this->index]['status'];
                        $coil->save();
                        //$this->component->coils = $this->client->coils;
                        $this->mqtt->interrupt();
                    }
                }
            }
        }
    }
}
