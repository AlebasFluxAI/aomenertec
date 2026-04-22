<?php

namespace App\Http\Livewire\V1\Admin\Client;

use App\Models\V1\Api\ApiKey;
use App\Models\V1\Client;
use App\Models\V1\RealTimeListener;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Monitoring extends Component
{
    use WithPagination;

    public $data_chart;
    public $data_frame;
    public $variables;
    public $client;
    public $reactive_variables;
    public $real_time_variables;
    public $time;
    public $clientAlerts;
    public $data_chart_result;
    public $model;
    public $liveMode = false;
    public $historicalRenderVersion = 0;
    protected $listeners = ['tabChange', 'toggleLiveMode'];

    public function mount(Client $client)
    {
        $this->model = $client;
        $this->clientAlerts = $this->client->clientAlerts;
//        foreach ($this->clientAlerts as &$alert) {
//            $alert->name = $alert->clientAlertConfiguration->getVariableName();
//        }
        $this->data_frame = collect(config('data-frame.data_frame'));
        $this->variables = collect(config('data-frame.variables'));
        $this->reactive_variables = $this->data_frame->whereIn('variable_id', [2, 14, 10])->toArray();
        $this->real_time_variables = $this->variables->where('real_time', true);
        $this->refreshHistoricalData();
    }

    /**
     * Alterna el modo tiempo real en el dashboard unificado.
     *
     * ON  → crea el RealTimeListener y dispatcha el job MQTT que activa
     *       el streaming en el firmware IpstaticV2.
     * OFF → delega en tabChange() que limpia el listener y envía el
     *       comando de desactivación al equipo.
     *
     * IMPORTANTE: Hacemos el trabajo AQUÍ (no vía emit a RealTimeChart)
     * porque con render SSR puro el componente hijo real-time-chart
     * se monta por primera vez en la misma request donde se activa
     * live mode — el emit se perdería porque aún no hay listener.
     * Replicamos la lógica de RealTimeChart::selectRealTime() para
     * preservar el contrato con el firmware sin depender del hijo.
     *
     * No modificamos topics MQTT, payloads ni API endpoints.
     */
    public function toggleLiveMode()
    {
        $this->liveMode = ! $this->liveMode;

        if ($this->liveMode) {
            $this->activateRealTime();
        } else {
            $this->tabChange();
            $this->refreshHistoricalData();
            $this->historicalRenderVersion++;
            $this->dispatchBrowserEvent('monitoring-historical-remount');
        }
    }

    private function refreshHistoricalData(): void
    {
        if (!$this->client) {
            return;
        }

        $this->time = 2;
        $firstDay = Carbon::now();
        $this->data_chart_result = $this->client->hourlyMicrocontrollerData()
            ->where('year', $firstDay->format('Y'))
            ->where('month', $firstDay->format('m'))
            ->where('day', 1)
            ->get();

        $this->data_chart = $this->client->hourlyMicrocontrollerData()
            ->orderBy('source_timestamp', 'desc')
            ->limit(24)
            ->get();

        if (count($this->data_chart) == 0) {
            $this->data_chart = $this->client->microcontrollerData()
                ->orderBy('source_timestamp', 'desc')
                ->limit(60)
                ->get();
            $this->time = 1;
        }
    }

    /**
     * Activa el streaming de tiempo real para el cliente actual.
     * Espejo de RealTimeChart::selectRealTime() simplificado para el
     * toggle unificado. Es idempotente: si ya hay listener del usuario,
     * no duplica nada. Si es el primer listener vivo del equipo, envía
     * el comando de activación al firmware.
     */
    private function activateRealTime(): void
    {
        if (!$this->client) {
            return;
        }

        $user = Auth::user();
        if (!$user) {
            return;
        }

        $clientConfig = $this->client->clientConfiguration()->first();
        if (!$clientConfig || !$clientConfig->active_real_time) {
            return;
        }

        $equipment = $this->client->equipments()->whereEquipmentTypeId(7)->first();
        if (!$equipment) {
            return;
        }

        // Limpieza de listeners zombie (>30 min sin actividad): evita
        // que sesiones abandonadas bloqueen la activación del streaming.
        RealTimeListener::whereEquipmentId($equipment->id)
            ->where('updated_at', '<', now()->subMinutes(30))
            ->delete();

        $alreadyListening = RealTimeListener::whereUserId($user->id)
            ->whereEquipmentId($equipment->id)
            ->exists();

        if ($alreadyListening) {
            return;
        }

        $new = RealTimeListener::create([
            'user_id' => $user->id,
            'equipment_id' => $equipment->id,
        ]);

        // Solo disparamos el comando al firmware si somos el primer
        // listener activo; si hay otros, ya hay streaming en curso.
        $othersExist = RealTimeListener::whereEquipmentId($equipment->id)
            ->where('id', '!=', $new->id)
            ->exists();

        if ($othersExist) {
            return;
        }

        $apiKey = ApiKey::first();
        if (!$apiKey) {
            return;
        }

        \App\Jobs\V1\Api\ConfigurationClient\SendRealTimeStatusJob::dispatch(
            $equipment->serial,
            1,
            $apiKey->api_key
        )->onQueue('spot2');
    }

    public function tabChange()
    {
        // Defensive guards: Livewire 2 hydrate can deliver null props when
        // the component re-syncs after a toggle race. Without these early
        // returns, `$this->client->...` or `Auth::user()->id` crash the
        // request with 500 and the historical dashboard never re-renders.
        if (!$this->client) {
            return;
        }

        $user = Auth::user();
        if (!$user) {
            return;
        }

        $clientConfig = $this->client->clientConfiguration()->first();

        if ($clientConfig && $clientConfig->active_real_time) {
            $equipment = $this->client->equipments()->whereEquipmentTypeId(7)->first();
            if ($equipment && RealTimeListener::whereUserId($user->id)
                ->whereEquipmentId($equipment->id)->exists()) {
                RealTimeListener::whereUserId($user->id)
                    ->whereEquipmentId(
                        $equipment->id
                    )->forceDelete();
                if (!RealTimeListener::whereEquipmentId($equipment->id)->exists()) {
                    $equipment= $this->client->equipments()->whereEquipmentTypeId(7)->first();
                    $apiKey =ApiKey::first();

                    if ($apiKey && $equipment) {
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
        return view('livewire.v1.admin.client.monitoring')
            ->extends('layouts.v1.app');
    }
}
