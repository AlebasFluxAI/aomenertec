<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring;

use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\RealTimeListener;
use App\ModulesAux\MQTT;
use Crc16\Crc16;
use App\Strategy\MqttSenderPattern\MqttCoilAckStrategy;
// use App\Strategy\MqttSenderPattern\MqttSenderContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
        $equipment= $this->client->equipments()->whereEquipmentTypeId(7)->first();
        $apiKey =ApiKey::first();
        $requestDetails = [
            'url' => 'https://aom.enerteclatam.com/api/v1/config/set-status-coil',
            'method' => 'GET',
            // 'headers' => $e->request->headers()->all(),
            'body' => [
                'serial' => $equipment->serial,
                'status' => !$this->coils[$index]['status'] ? 1: 0
            ],
            'apiKey' => $apiKey->api_key
        ];



        try {
            $mqtt = MQTT::connection('default', 'null');
            $mqttCoilAckStrategy = new MqttCoilAckStrategy($mqtt, $this);
            $mqttCoilAckStrategy->setIndex($index);
            $mqttCoilAckStrategy->registerLoopEventHandler();
            $mqttCoilAckStrategy->subscribe();
            $mqttCoilAckStrategy->fetchDataFromAPI($requestDetails);


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
