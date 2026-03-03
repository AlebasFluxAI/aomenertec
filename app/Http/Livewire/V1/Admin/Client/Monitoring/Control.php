<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring;

use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Api\EventLog;
use App\Models\V1\Client;
use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\RealTimeListener;
use App\ModulesAux\MQTT;
use App\Strategy\MqttSenderPattern\FetchDataApiStrategy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use PhpMqtt\Client\Exceptions\MqttClientException;

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

        $equipment= $this->client->equipments()->whereEquipmentTypeId(7)->first();
        $apiKey =ApiKey::first();

        $requestDetails = [
            'url' => config('aom.api_url') . config('aom.api_config_path') . '/get-status-coil',
            'method' => 'GET',
            'body' => [
                'serial' => $equipment->serial,
            ],
            'apiKey' => $apiKey->api_key
        ];
    }

    public function confirmAction($index)
    {
        $equipment= $this->client->equipments()->whereEquipmentTypeId(7)->first();
        $apiKey =ApiKey::first();
        $requestDetails = [
            'url' => config('aom.api_url') . config('aom.api_config_path') . '/set-status-coil',
            'method' => 'GET',
            'body' => [
                'serial' => $equipment->serial,
                'status' => !$this->coils[$index]['status'] ? 1: 0
            ],
            'apiKey' => $apiKey->api_key
        ];
        try {
            $mqtt = MQTT::connection('default', EventLog::EVENT_SET_STATUS_COIL.'-'.$equipment->serial.'aom-channel');
            $mqttCoilAckStrategy = new FetchDataApiStrategy($mqtt, $this);
            $mqttCoilAckStrategy->setIndex($index);
            $mqttCoilAckStrategy->fetchDataFromAPI($requestDetails);
            $mqttCoilAckStrategy->registerLoopEventHandler();
            $mqttCoilAckStrategy->subscribe($equipment, 3);
        } catch (MqttClientException $e) {
            $this->emit('changeCheck', ['index' => $this->component->coils[$this->index]['id'], 'flag' => false]);
            $this->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Intente nuevamente"]);
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
        $clientConfig = $this->client->clientConfiguration()->first();

        if ($clientConfig && $clientConfig->active_real_time && $clientConfig->real_time_flag) {
            $equipment = $this->client->equipments()->whereEquipmentTypeId(7)->first();

            if ($equipment && RealTimeListener::whereUserId(Auth::user()->id)
                ->whereEquipmentId($equipment->id)->exists()) {
                RealTimeListener::whereUserId(Auth::user()->id)
                    ->whereEquipmentId($equipment->id)->forceDelete();

                if (!RealTimeListener::whereEquipmentId($equipment->id)->exists()) {
                    $apiKey = ApiKey::first();

                    if ($apiKey) {
                        try {
                            // Llamada interna (localhost) para desactivar real-time.
                            // Fire-and-forget: no necesita esperar ACK del dispositivo.
                            $internalUrl = 'http://localhost' . config('aom.api_config_path') . '/set-status-real-time';

                            Http::withHeaders([
                                'x-api-key' => $apiKey->api_key,
                            ])->timeout(10)->get($internalUrl, [
                                'serial' => $equipment->serial,
                                'status' => 0
                            ]);

                            Log::info('Control: deactivation command sent for serial ' . $equipment->serial);
                        } catch (\Throwable $e) {
                            Log::error('Control: error deactivating real-time for serial ' . $equipment->serial . ': ' . $e->getMessage());
                            $this->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Intente nuevamente"]);
                        }
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
