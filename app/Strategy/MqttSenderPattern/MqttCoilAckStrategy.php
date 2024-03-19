<?php

namespace App\Strategy\MqttSenderPattern;

use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\EquipmentType;
use PhpMqtt\Client\MqttClient;

class MqttCoilAckStrategy implements MqttSenderInterface
{
    use MqttSenderTrait;

    public const EVENT = "coil_ack";
    private $index;


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

    public function subscribeContext($message, $equipment)
    {
        $webhookResponse = json_decode($message, true);
        if ($webhookResponse['notification_type_id'] == 3){
            if ($webhookResponse['success'] == 1){
                if($equipment->serial == $webhookResponse['serial']){
                    $this->component->coils[$this->index]['status'] = $webhookResponse['data']['status_coil'];
                    $this->component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => $webhookResponse['message']]);
                    $coil = ClientDigitalOutput::find($this->component->coils[$this->index]['id']);
                    $this->component->emit('changeCheck', ['index' => $coil->id, 'flag' => true]);
                    $coil->status = $webhookResponse['data']['status_coil'];
                    $coil->save();
                    //$this->$this->component->coils = $this->client->coils;
                    $this->mqtt->interrupt();
                }
            } else{
                $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => $webhookResponse['message']]);
                $coil = ClientDigitalOutput::find($this->component->coils[$this->index]['id']);
                $this->emit('changeCheck', ['index' => $coil->id, 'flag' => false]);
                $this->mqtt->interrupt();
            }
        } elseif($webhookResponse['notification_type_id'] == 20){
            if($equipment->serial == $webhookResponse['serial']){
                $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => $webhookResponse['message']]);
                $coil = ClientDigitalOutput::find($this->component->coils[$this->index]['id']);
                $this->emit('changeCheck', ['index' => $coil->id, 'flag' => false]);
                $this->mqtt->interrupt();
            }
        }
        elseif($webhookResponse['notification_type_id'] == 21){
            if($equipment->serial == $webhookResponse['serial']){
                $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => $webhookResponse['message']]);
                $coil = ClientDigitalOutput::find($this->component->coils[$this->index]['id']);
                $this->component->emit('changeCheck', ['index' => $coil->id, 'flag' => false]);
                $this->mqtt->interrupt();
            }
        }
    }
}
