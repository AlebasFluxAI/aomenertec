<?php

namespace App\Strategy\MqttSenderPattern;

use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\EquipmentType;
use App\ModulesAux\MQTT;
use PhpMqtt\Client\Exceptions\MqttClientException;
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
                $json = $event['json'];
                foreach ($json as $item){

                    if ($item['variable_name'] == 'notification_type_id') {

                        if ($webhookResponse['notification_type_id'] == $item['value']) {

                            if ($webhookResponse['success'] == 1) {
                                if ($equipment->serial == $webhookResponse['serial']) {
                                    if ($notificationTypeId == 3) {
                                        $this->component->coils[$this->index]['status'] = !$this->component->coils[$this->index]['status'];
                                        foreach ($this->component->coils as $output){
                                            $output->status = $this->component->coils[$this->index]['status'];
                                            $output->save();
                                        }
                                        $this->component->emit('changeCheck', ['index' => $this->component->coils[$this->index]['id'], 'flag' => true]);
                                    } elseif ($notificationTypeId == 4) {
                                        $this->component->coils[0]['status'] = $json['data']['status_coil'] == 1;
                                    } elseif ($notificationTypeId == 10){
                                        foreach ($this->component->client_config_alert as $index => $item) {
                                            if ($index == "client_notification_type") {
                                                continue;
                                            }
                                            $item->save();
                                        }
                                    } elseif ($notificationTypeId == 43){
                                        $this->component->status = $webhookResponse['data']['status'] == 1;
                                        if($this->component->status){
                                            $this->component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => $webhookResponse['message']]);
                                        } else{
                                            $this->component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => 'El equipo recibio la solicitud pero no esta disponible para la actualización, intente nuevamente']);
                                        }
                                    }
                                    if($notificationTypeId != 43){
                                        $this->component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => $webhookResponse['message']]);
                                    }
                                    $this->mqtt->interrupt();
                                    if ($notificationTypeId == 46){
                                        try {
                                            $mqtt = MQTT::connection('default', 'null');
                                            $mqttCoilAckStrategy = new FetchDataApiStrategy($mqtt, $this->component);
                                            $mqttCoilAckStrategy->fetchDataFromAPI($this->component->requestDetails);
                                            $mqttCoilAckStrategy->registerLoopEventHandler();
                                            $mqttCoilAckStrategy->subscribe($equipment, 58);
                                        } catch (MqttClientException $e) {
                                            $this->component->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Intente nuevamente"]);
                                        }
                                    }
                                }
                            } else {
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
