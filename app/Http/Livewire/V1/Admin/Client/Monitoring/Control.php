<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring;

use App\Models\V1\Client;
use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\RealTimeListener;
use App\ModulesAux\MQTT;
use Crc16\Crc16;
use App\Strategy\MqttSenderPattern\MqttCoilAckStrategy;
use App\Strategy\MqttSenderPattern\MqttSenderContext;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\MqttClient;

class Control extends Component
{

    public $client;
    public $coils;

    protected $rules = [
        'coils.*.id' => 'required',
        'coils.*.number' => 'required',
        'coils.*.name' => 'required',
        'coils.*.status' => 'required',
        'coils.*.control_type' => 'required',
    ];
    protected $listeners = ['selectControl'];

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->coils = $this->client->digitalOutputs;
    }

    public function confirmAction($index)
    {
        $equipment = $this->client->equipments()->whereEquipmentTypeId(1)->first();
        $topic = "v1/mc/config/" . $equipment->serial;
        $coil_id = pack('C', $this->coils[$index]['number']);
        $event_id = pack('C', 7);

        if ($this->coils[$index]['status']) {
            $status = pack('C',  0);

        } else {
            $status = pack('C',  1);
        }
        $message = $event_id.$coil_id.$status;
        $crc = Crc16::XMODEM($message);
        $crc_pack = pack('v', $crc);
        $message = $message.$crc_pack;

        try {
            $mqtt = MQTT::connection('default', 'default');
            $mqtt->publish($topic, $message);
//            $mqttCoilAckStrategy = new MqttCoilAckStrategy($mqtt, $this);
//            $mqttCoilAckStrategy->setIndex($index);
//            $mqttCoilAckStrategy->setTopic();
//            $mqttCoilAckStrategy->setMessage();
//            $mqttCoilAckStrategy->publish();
//            $mqttCoilAckStrategy->registerLoopEventHandler();
//            $mqttCoilAckStrategy->subscribe();



            $mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) use ($index) {
                if ($elapsedTime >= 10) {
                    $this->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Fallo la conexion"]);
                    $mqtt->interrupt();
                    $this->emit('changeCheck', ['index' => $index, 'flag' => false]);
                }
            });
            $mqtt->subscribe('v1/mc/ack', function (string $topic, string $message) use ($index, $mqtt, &$result, $equipment) {
                $crc_message = substr($message,-2);
                $data_crc = substr($message, 0, -2);
                $crc = Crc16::XMODEM($data_crc);
                $crc_pack = pack('v', $crc);
                if ($crc_pack == $crc_message){
                    $event = unpack('C', $message[0])[1];
                    if ($event == 16){
                        $message_hex = bin2hex($message);
                        $did = substr($message, (1), (9));
                        $did_unpack = unpack('P', $did)[1];
                        if($equipment->serial == $did_unpack){
                            $this->coils[$index]['status'] = !$this->coils[$index]['status'];
                            $this->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Accion realizada"]);
                            $coil = ClientDigitalOutput::find($this->coils[$index]['id']);
                            $this->emit('changeCheck', ['index' => $coil->id, 'flag' => true]);
                            $coil->status = $this->coils[$index]['status'];
                            $coil->save();
                            //$this->coils = $this->client->coils;
                            $mqtt->interrupt();
                        }
                    }
                }
            }, 1);
            $mqtt->loop(true);
            $mqtt->disconnect();


        } catch (MqttClientException $e) {
        }
    }

    public function updatedCoils($value, $key)
    {
        $variable = explode(".", $key);
        if ($variable[1] == "name") {
            $coil = ClientDigitalOutput::find($this->coils[$variable[0]]['id']);
            $coil->name = $value;
            $coil->save();
            $this->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Nombre actualizado"]);
        }
    }

    public function selectControl()
    {
        if ($this->client->clientConfiguration()->first()->active_real_time) {
            if ($this->client->clientConfiguration()->first()->real_time_flag) {
                $equipment = $this->client->equipments()->whereEquipmentTypeId(1)->first();
                if (RealTimeListener::whereUserId(Auth::user()->id)
                    ->whereEquipmentId($equipment->id)->exists()) {
                    RealTimeListener::whereUserId(Auth::user()->id)
                        ->whereEquipmentId(
                            $equipment->id
                        )->delete();
                    if (!RealTimeListener::whereEquipmentId($equipment->id)->exists()) {
                        $message = "{'did':" . $equipment->serial . ",'realTimeFlag':false}";
                        $topic = 'mc/config/' . $equipment->serial;
                        MQTT::publish($topic, $message);
                        MQTT::disconnect();
                    }
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.v1.admin.client.monitoring.control')
            ->extends('layouts.v1.app');
    }
}
