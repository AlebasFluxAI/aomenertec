<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\V1\ClientAlert;
use Livewire\Component;

class ClientAlertIndexService extends Singleton
{

    public function mount(Component $component, $client)
    {
        $component->model = $client;
    }

    public function getData(Component $component, $alert)
    {
        if ($alert){
            $clientAlerts = $component->model->clientAlerts()->where('type', '!=', ClientAlert::INFORMATIVE)->pagination();
            foreach ($clientAlerts as &$alert) {
                $alert->name = $alert->clientAlertConfiguration->getVariableName();
            }
        } else{
            $clientAlerts = $component->model->clientAlerts()->where('type', ClientAlert::INFORMATIVE)->pagination();
            foreach ($clientAlerts as &$alert) {
                $request_json = json_decode($alert->eventLog->request_json, true);
                $alert->message = $request_json['message'];
            }
        }

        return $clientAlerts;
    }

    public function deleteAlert(Component $component, $alertId)
    {
        ClientAlert::find($alertId)->delete();
        ToastEvent::launchToast($component, "show", "success", "Alerta eliminada");
    }
}
