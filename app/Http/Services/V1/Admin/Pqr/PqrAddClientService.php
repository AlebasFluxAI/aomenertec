<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Pqr;
use App\Models\V1\User;
use Livewire\Component;

class PqrAddClientService extends Singleton
{
    public function mount(Component $component, Pqr $pqr)
    {

        $component->fill([
            "model" => $pqr,
            "clients" => $this->clientAsKeyValue($this->getClients())
        ]);
    }

    private function clientAsKeyValue($clients)
    {
        return (array_merge(
            [[
                "key" => "Seleccione el tipo de equipo ...",
                "value" => null
            ]],
            ($clients->map(function ($client) {
                return [
                    "key" => $client->identification . " - " . $client->name,
                    "value" => $client->id,
                ];
            }))->toArray()
        ));
    }

    private function getClients()
    {
        $model = User::getUserModel();
        if ($model::class == Admin::class) {
            return $model->getCurrentEnabledClients()->get();
        }
        if ($model::class == NetworkOperator::class) {
            return $model->clients;
        }
        return [];
    }

    public function submitForm(Component $component)
    {
        $component->model->update([
            "client_id" => $component->client_id
        ]);
        $component->redirectRoute("administrar.v1.peticiones.detalles", ["pqr" => $component->model->id]);

    }

}
