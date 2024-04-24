<?php

namespace App\Strategy\MqttSenderPattern;

use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\ClientDigitalOutputAlertConfiguration;
use App\Models\V1\EquipmentType;
use App\ModulesAux\MQTT;
use App\Notifications\Alert\AlertControlNotification;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\MqttClient;

class AlertControlApiStrategy implements MqttSenderInterface
{
    use MqttSenderTrait;

    public const EVENT = "coil_ack";
    private $index;


    public function registerLoopEventHandlerContext(float $elapsedTime, MqttClient $mqtt)
    {
        if ($elapsedTime >= 30) {
            $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Fallo la conexion"]);
            $mqtt->interrupt();
            $this->component->emit('changeCheck', ['index' => $this->index, 'flag' => false]);
        }
    }

    public function setIndex($index)
    {
        $this->index = $index;
    }

    public function subscribeContext($message, $equipment,$notificationTypeId)
    {

        $webhookEvents = config('data-frame.webhook_events');
        $webhookResponse = json_decode($message, true);
        if($webhookResponse['notification_type_id'] == 46 || $webhookResponse['notification_type_id'] == 58) {
            foreach ($webhookEvents as $event) {
                if ($event['notification_type_id'] == $webhookResponse['notification_type_id']) {
                    $json = $event['json'];
                    foreach ($json as $item) {
                        if ($item['variable_name'] == 'notification_type_id') {
                            if ($webhookResponse['notification_type_id'] == $item['value']) {
                                if ($webhookResponse['success'] == 1) {
                                    if ($equipment->serial == $webhookResponse['serial']) {
                                        $this->component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => $webhookResponse['message']]);

                                        if ($webhookResponse['notification_type_id'] == 58) {
                                            $this->mqtt->interrupt();

                                        }

                                    }
                                } else {
                                    $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => $webhookResponse['message']]);
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
}
