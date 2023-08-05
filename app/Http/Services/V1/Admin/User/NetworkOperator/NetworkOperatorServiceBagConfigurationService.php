<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Traits\NetworkOperatorPriceTrait;
use App\Models\V1\Client;
use App\Models\V1\NetworkOperator;
use App\Models\V1\PhotovoltaicPrice;
use App\Models\V1\Stratum;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NetworkOperatorServiceBagConfigurationService extends Singleton
{
    use NetworkOperatorPriceTrait;

    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
            'pqr_bag' => $model->pqr_initial_bag,
            'work_order_hours' => $model->work_order_initial_bag,
            'billing_day' => $model->billing_day,
            "has_billable_pqr" => $model->billableServices ? $model->billableServices->has_billable_pqr : false,
            "has_billable_orders" => $model->billableServices ? $model->billableServices->has_billable_orders : false,
            "has_billable_clients" => $model->billableServices ? $model->billableServices->has_billable_clients : false,
            "pqr_price" => $model->billableServices ? $model->billableServices->pqr_price : false,
            "orders_price" => $model->billableServices ? $model->billableServices->orders_price : false,
        ]);
    }


    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->model->update([
                "pqr_initial_bag" => $component->pqr_bag,
                "work_order_initial_bag" => $component->work_order_hours,
                "billing_day" => $component->billing_day
            ]);

            if ($billableService = $component->model->billableServices) {
                $billableService->update([
                    "orders_price" => $component->orders_price,
                    "pqr_price" => $component->pqr_price,
                ]);
            } else {
                $component->model->billableServices()->create([
                    "pqr_price" => $component->pqr_price,
                    "orders_price" => $component->orders_price,
                ]);
            }
        });
        $component->redirectRoute("administrar.v1.usuarios.operadores.detalles", ["networkOperator" => $component->model->id]);

    }

    public function updatedHasBillablePqr(Component $component)
    {
        ToastEvent::launchToast($component, "show", "success", "Servicio modificado");
        if ($billableService = $component->model->billableServices) {
            $billableService->update([
                "has_billable_pqr" => $component->has_billable_pqr,
            ]);
            return;
        }
        $component->model->billableServices()->create([
            "has_billable_pqr" => $component->has_billable_pqr,
        ]);
    }

    public function updatedHasBillableOrders(Component $component)
    {
        ToastEvent::launchToast($component, "show", "success", "Servicio modificado");
        if ($billableService = $component->model->billableServices) {
            $billableService->update([
                "has_billable_orders" => $component->has_billable_orders,
            ]);
            return;
        }
        $component->model->billableServices()->create([
            "has_billable_orders" => $component->has_billable_orders,
        ]);

    }

    public function updatedHasBillableClients(Component $component)
    {
        ToastEvent::launchToast($component, "show", "success", "Servicio modificado");
        if ($billableService = $component->model->billableServices) {
            $billableService->update([
                "has_billable_clients" => $component->has_billable_clients,
            ]);
            return;
        }
        $component->model->billableServices()->create([
            "has_billable_clients" => $component->has_billable_clients,
        ]);

    }
}
