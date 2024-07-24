<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\ClientType;
use App\Models\V1\HourlyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IndexClientService extends Singleton
{
    public function mount(Component $component)
    {
        $clientType = ClientType::first();
        $component->fill([
            "clientType" => $clientType->type,
            "filterAuxValue" => $clientType->id
        ]);
    }

    public function getClients()
    {
        return Client::get()->pagination();
    }

    public function conditionalMonitoring(Component $component, $modelId)
    {
        return !HourlyMicrocontrollerData::whereClientId($modelId)->exists();
    }

    public function conditionalDeleteClient(Component $component, $modelId)
    {
        return MicrocontrollerData::whereClientId($modelId)->exists();
    }

    public function setFilter(Component $component, $filterValue)
    {
        $clientType = ClientType::whereType($filterValue)->first();
        $component->filterAuxColumn = "client_type_id";
        $component->filterAuxValue = $clientType->id;
        $component->clientType = $filterValue;


    }

    public function deleteClient(Component $component, $modelId)
    {
        Client::find($modelId)->delete();
        $this->getData($component);
    }

    public function delete(Component $component, $clientId)
    {
        Client::find($clientId)->delete();
        $component->emitTo('livewire-toast', 'show', "Equipo {$clientId} eliminado exitosamente");
        $component->reset();
    }

    public function getData(Component $component)
    {
        if (User::getUserModel()::class == Seller::class) {
            $seller = User::getUserModel();
            if ($component->filter) {
                return (Client::whereIn('id', $seller->clientSellers()->pluck('client_id'))
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%'))
                    ->where($component->filterAuxColumn, $component->filterAuxValue)->pagination();
            }
            return (Client::whereIn('id', $seller->clientSellers()->pluck('client_id')))
                ->where($component->filterAuxColumn, $component->filterAuxValue)
                ->pagination();
        }

        if (User::getUserModel()::class == NetworkOperator::class) {
            $networkOperator = User::getUserModel();
            if ($component->filter) {
                return ($networkOperator->clients()->where($component->filterCol, 'ilike', '%' . $component->filter . '%'))
                    ->where($component->filterAuxColumn, $component->filterAuxValue)->pagination();
            }
            return $networkOperator->clients()
                ->where($component->filterAuxColumn, $component->filterAuxValue)
                ->pagination();
        }

        if (User::getUserModel()::class == Supervisor::class) {
            $supervisor = User::getUserModel();
            if ($component->filter) {
                return (Client::whereIn('id', $supervisor->clients->pluck('id'))
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%'))
                    ->where($component->filterAuxColumn, $component->filterAuxValue)->pagination();
            }
            return (Client::whereIn('id', $supervisor->clients->pluck('id')))
                ->where($component->filterAuxColumn, $component->filterAuxValue)
                ->pagination();
        }

        if (User::getUserModel()::class == Admin::class) {
            $admin = User::getUserModel();
            if ($component->filter) {
                return (Client::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))
                    ->orWhere("admin_id", Auth::user()->getAdmin()->id)
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%')
                )->where($component->filterAuxColumn, $component->filterAuxValue)
                    ->pagination();
            }
            return Client::where(function ($query) use ($admin) {
                $query->whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))
                    ->orWhere("admin_id", Auth::user()->getAdmin()->id);
            })->where($component->filterAuxColumn, $component->filterAuxValue)
                ->pagination();
        }


        if (User::getUserModel()::class == Technician::class) {
            $technician = User::getUserModel();
            if ($component->filter) {
                return (Client::whereIn('id', $technician->clientTechnicians->pluck("client_id"))
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%')
                )->where($component->filterAuxColumn, $component->filterAuxValue)
                    ->pagination();
            }
            return (Client::whereIn('id', $technician->clientTechnicians->pluck("client_id"))
            )->where($component->filterAuxColumn, $component->filterAuxValue)
                ->pagination();
        }

        if ($component->filterAuxColumn) {
            return Client::where($component->filterAuxColumn, $component->filterAuxValue)->pagination();
        } else {
            return Client::pagination();
        }

    }


    public function disableClient(Component $component, $clientId)
    {
        DB::transaction(function () use ($clientId, $component) {
            Client::find($clientId)->disableClient();
            ToastEvent::launchToast($component, "show", "success", "Cliente desactivado exitosamente");
        });
        $component->reset();
    }

}
