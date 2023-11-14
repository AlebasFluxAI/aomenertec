<?php

namespace App\Http\Services\V1\Admin\ClientDisabled;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\ClientType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use App\Scope\ClientEnabledScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IndexClientDisabledService extends Singleton
{
    public function setFilter(Component $component, $filterValue)
    {
        $clientType = ClientType::whereType($filterValue)->first();
        $component->filterAuxColumn = "client_type_id";
        $component->filterAuxValue = $clientType->id;
        $component->clientType = $filterValue;


    }

    public function createActivationWorkOrder(Component $component, $clientId)
    {

        DB::transaction(function () use ($component, $clientId) {
            $client = Client::withoutGlobalScope(ClientEnabledScope::class)->find($clientId);
            $client->update([
                "activation_requested" => true,
            ]);
            $workOrder = $client->workOrders()->create([
                "description" => "Orden de servicio para activacion de cliente " . $client->alias,
                "type" => WorkOrder::WORK_ORDER_TYPE_ENABLE_CLIENT,
                "technician_id" => $client->technician()->first()->technician->id,
                "days" => 0,
                "hours" => 0,
                "minutes" => 10,
            ]);
            $component->redirectRoute("administrar.v1.ordenes_de_servicio.detalle", ["workOrder" => $workOrder->id]);
        });

    }

    public function createActivationWorkOrderConditional(Component $component, $clientId)
    {
        return Client::withoutGlobalScope(ClientEnabledScope::class)->find($clientId)->activation_requested;
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
                return (Client::withoutGlobalScope(ClientEnabledScope::class)->whereStatus(Client::CLIENT_STATUS_DISABLED)->whereIn('id', $supervisor->clients->pluck('id'))
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%'))
                    ->where($component->filterAuxColumn, $component->filterAuxValue)->pagination();
            }
            return (Client::withoutGlobalScope(ClientEnabledScope::class)->whereStatus(Client::CLIENT_STATUS_DISABLED)->whereIn('id', $supervisor->clients->pluck('id')))
                ->where($component->filterAuxColumn, $component->filterAuxValue)
                ->pagination();
        }

        if (User::getUserModel()::class == Admin::class) {
            $admin = User::getUserModel();
            if ($component->filter) {
                return (Client::withoutGlobalScope(ClientEnabledScope::class)->whereStatus(Client::CLIENT_STATUS_DISABLED)->whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))
                    ->orWhere("admin_id", Auth::user()->getAdmin()->id)
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%')
                )->where($component->filterAuxColumn, $component->filterAuxValue)
                    ->pagination();
            }
            return Client::withoutGlobalScope(ClientEnabledScope::class)->whereStatus(Client::CLIENT_STATUS_DISABLED)->where(function ($query) use ($admin) {
                $query->whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))
                    ->orWhere("admin_id", Auth::user()->getAdmin()->id);
            })->where($component->filterAuxColumn, $component->filterAuxValue)
                ->pagination();
        }


        if (User::getUserModel()::class == Technician::class) {
            $technician = User::getUserModel();
            if ($component->filter) {
                return (Client::withoutGlobalScope(ClientEnabledScope::class)->whereStatus(Client::CLIENT_STATUS_DISABLED)->whereIn('id', $technician->clients->pluck("id"))
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%')
                )->where($component->filterAuxColumn, $component->filterAuxValue)
                    ->pagination();
            }
            return (Client::withoutGlobalScope(ClientEnabledScope::class)->whereStatus(Client::CLIENT_STATUS_DISABLED)->whereIn('id', $technician->clients->pluck("id"))
            )->where($component->filterAuxColumn, $component->filterAuxValue)
                ->pagination();
        }

        if ($component->filterAuxColumn) {
            return Client::withoutGlobalScope(ClientEnabledScope::class)->whereStatus(Client::CLIENT_STATUS_DISABLED)->where($component->filterAuxColumn, $component->filterAuxValue)->pagination();
        } else {
            return Client::withoutGlobalScope(ClientEnabledScope::class)->whereStatus(Client::CLIENT_STATUS_DISABLED)->pagination();
        }

    }

    public function enableClient(Component $component, $clientId)
    {

        DB::transaction(function () use ($clientId, $component) {
            Client::withoutGlobalScope(ClientEnabledScope::class)->find($clientId)->enableClient();
            ToastEvent::launchToast($component, "show", "success", "Cliente desactivado exitosamente");
        });
        $component->reset();
    }
}
