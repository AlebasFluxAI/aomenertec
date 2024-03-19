<?php

namespace App\Strategy\MqttSenderPattern;

use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\EquipmentType;
use PhpMqtt\Client\MqttClient;

class MqttBrokerCredentialsStrategy implements MqttSenderInterface
{
    use MqttSenderTrait;

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
                    $this->component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => $webhookResponse['message']]);
                    $this->mqtt->interrupt();
                }
            } else{
                $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => $webhookResponse['message']]);
                $this->component->emit('changeCheck', ['index' => $this->component->coils[$this->index]['id'], 'flag' => false]);
                $this->mqtt->interrupt();
            }
        } elseif($webhookResponse['notification_type_id'] == 20){
            if($equipment->serial == $webhookResponse['serial']){
                $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => $webhookResponse['message']]);
                $this->component->emit('changeCheck', ['index' => $this->component->coils[$this->index]['id'], 'flag' => false]);
                $this->mqtt->interrupt();
            }
        }
        elseif($webhookResponse['notification_type_id'] == 21){
            if($equipment->serial == $webhookResponse['serial']){
                $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => $webhookResponse['message']]);
                $this->component->emit('changeCheck', ['index' => $this->component->coils[$this->index]['id'], 'flag' => false]);
                $this->mqtt->interrupt();
            }
        }
    }
}
