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

    public function subscribeContext($message, $equipment, $notificationTypeId)
    {
        $webhookEvents = config('data-frame.webhook_events');
        $webhookResponse = json_decode($message, true);
        foreach ($webhookEvents as $event){
            if ($event['notification_type_id'] == $notificationTypeId){
                if ($webhookResponse['notification_type_id'] == $event['json']['notification_type_id']) {
                    if ($webhookResponse['success'] == 1) {
                        if ($equipment->serial == $webhookResponse['serial']) {
                            $this->component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => $webhookResponse['message']]);
                            $this->component->emit('changeCheck', ['index' => $this->component->coils[$this->index]['id'], 'flag' => true]);
                            $this->mqtt->interrupt();
                        }
                    } else {
                        $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => $webhookResponse['message']]);
                        $coil = ClientDigitalOutput::find($this->component->coils[$this->index]['id']);
                        $this->component->emit('changeCheck', ['index' => $coil->id, 'flag' => false]);
                        $this->mqtt->interrupt();
                    }
                }
            }
        }
    }
}
