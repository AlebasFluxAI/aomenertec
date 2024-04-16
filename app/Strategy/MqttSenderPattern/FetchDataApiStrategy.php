<?php

namespace App\Strategy\MqttSenderPattern;

use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\EquipmentType;
use PhpMqtt\Client\MqttClient;

class FetchDataApiStrategy implements MqttSenderInterface
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
                dd($webhookResponse, $notificationTypeId, $event);

                $json = $event['json'];
                foreach ($json as $item){

                    if ($item['variable_name'] == 'notification_type_id') {

                        if ($webhookResponse['notification_type_id'] == $item['value']) {

                            if ($webhookResponse['success'] == 1) {
                                if ($equipment->serial == $webhookResponse['serial']) {
                                    if ($notificationTypeId == 3) {
                                        $this->component->coils[$this->index]['status'] = !$this->component->coils[$this->index]['status'];
                                        $this->component->emit('changeCheck', ['index' => $this->component->coils[$this->index]['id'], 'flag' => true]);
                                    } elseif ($notificationTypeId == 10){
                                        foreach ($this->component->client_config_alert as $index => $item) {
                                            if ($index == "client_notification_type") {
                                                continue;
                                            }
                                            $item->save();
                                        }
                                    }
                                    $this->component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => $webhookResponse['message']]);
                                    $this->mqtt->interrupt();
                                }
                            } else {
                                dd($webhookResponse);
                                $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => $webhookResponse['message']]);
                                if ($notificationTypeId == 3) {
                                    $this->component->coils[$this->index]['status'] = $this->component->coils[$this->index]['status'];
                                    $this->component->emit('changeCheck', ['index' => $this->component->coils[$this->index]['id'], 'flag' => false]);
                                }
                                $this->mqtt->interrupt();
                            }
                        }
                        break;
                    }
                }
            }
        }
    }
}
