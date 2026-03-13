<?php

namespace App\Http\Livewire\V1\Admin\Client\Monitoring;

use App\Jobs\V1\Api\ConfigurationClient\SendCoilStatusJob;
use App\Models\V1\Api\ApiKey;
use App\Models\V1\Client;
use App\Models\V1\ClientDigitalOutput;
use App\Models\V1\RealTimeListener;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

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

    public function getListeners()
    {
        $clientId = $this->client->id ?? null;
        if ($clientId) {
            return [
                'selectControl',
                "echo:config-ack.{$clientId},.configAckResponse" => 'handleConfigAck',
            ];
        }
        return ['selectControl'];
    }

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->coils = $this->client->digitalOutputs;
    }

    public function confirmAction($index)
    {
        $equipment = $this->client->equipments()->whereEquipmentTypeId(7)->first();

        if (!$equipment) {
            $this->emit('changeCheck', ['index' => $this->coils[$index]['id'] ?? $index, 'flag' => false]);
            $this->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "No se encontró equipo asociado al cliente"]);
            return;
        }

        $apiKey = ApiKey::first();

        if (!$apiKey) {
            $this->emit('changeCheck', ['index' => $this->coils[$index]['id'] ?? $index, 'flag' => false]);
            $this->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "No se encontró API key configurada"]);
            return;
        }

        $newStatus = !$this->coils[$index]['status'] ? 1 : 0;

        try {
            SendCoilStatusJob::dispatch(
                $equipment->serial,
                $newStatus,
                $apiKey->api_key
            )->onQueue('spot2');

            // Immediate feedback toast — ACK result arrives async via Echo
            $this->emitTo('livewire-toast', 'show', [
                'type' => 'success',
                'message' => "Comando enviado al equipo, esperando confirmación del dispositivo"
            ]);
        } catch (\Exception $e) {
            Log::error("Control::confirmAction dispatch error: {$e->getMessage()}");
            $this->emit('changeCheck', ['index' => $this->coils[$index]['id'] ?? $index, 'flag' => false]);
            $this->emitTo('livewire-toast', 'show', ['type' => 'error', 'message' => "Error al enviar el comando, intente nuevamente"]);
        }
    }

    /**
     * Handle async ACK response from CheckAckLogJob via Laravel Echo broadcast.
     */
    public function handleConfigAck($payload)
    {
        $data = $payload['data'] ?? $payload;
        $status = $data['status'] ?? 'error';
        $message = $data['message'] ?? 'Respuesta del equipo recibida';
        $event = $data['event'] ?? null;

        // Only handle coil ACK events
        if ($event !== 'set-status-coil') {
            return;
        }

        if ($status === 'success') {
            // Update coil status in the UI — toggle all coils (same as original logic)
            foreach ($this->coils as $index => $coil) {
                $this->coils[$index]['status'] = !$this->coils[$index]['status'];
                $digitalOutput = ClientDigitalOutput::find($this->coils[$index]['id']);
                if ($digitalOutput) {
                    $digitalOutput->status = $this->coils[$index]['status'];
                    $digitalOutput->save();
                }
            }
            $firstCoilId = $this->coils[0]['id'] ?? 0;
            $this->emit('changeCheck', ['index' => $firstCoilId, 'flag' => true]);
        } else {
            $firstCoilId = $this->coils[0]['id'] ?? 0;
            $this->emit('changeCheck', ['index' => $firstCoilId, 'flag' => false]);
        }

        $this->emitTo('livewire-toast', 'show', [
            'type' => $status === 'success' ? 'success' : 'error',
            'message' => $message,
        ]);
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
                        // Dispatch background job to avoid Http::localhost deadlock
                        // on single-threaded php artisan serve.
                        \App\Jobs\V1\Api\ConfigurationClient\SendRealTimeStatusJob::dispatch(
                            $equipment->serial,
                            0,
                            $apiKey->api_key
                        )->onQueue('spot2');
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
