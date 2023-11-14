<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Model\V1\BillingService;
use App\Models\Traits\NetworkOperatorPriceTrait;
use App\Models\V1\ClientType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NetworkOperatorServiceBagConfigurationService extends Singleton
{
    use NetworkOperatorPriceTrait;

    public function mount(Component $component, $model)
    {
        $clientPrices = $model->networkOperatorClientPrices;
        $component->fill([
            'model' => $model,
            'pqr_bag' => $model->billableServices ? $model->billableServices->pqr_initial_bag : 0,
            'work_order_hours' => $model->billableServices ? $model->billableServices->work_order_initial_bag : 0,
            'billing_day' => $model->billableServices ? $model->billableServices->billing_day : 0,
            "has_billable_pqr" => $model->billableServices ? $model->billableServices->has_billable_pqr : false,
            "has_billable_orders" => $model->billableServices ? $model->billableServices->has_billable_orders : false,
            "has_billable_clients" => $model->billableServices ? $model->billableServices->has_billable_clients : false,
            "pqr_price" => $model->billableServices ? $model->billableServices->pqr_price : false,
            "orders_price" => $model->billableServices ? $model->billableServices->orders_price : false,
            "initial_package_pqr_price" => $model->billableServices ? $model->billableServices->initial_package_pqr_price : false,
            "initial_package_orders_price" => $model->billableServices ? $model->billableServices->initial_package_orders_price : false,
            "min_clients" => $model->billableServices ? $model->billableServices->min_clients : false,
            "min_client_value" => $model->billableServices ? $model->billableServices->min_client_value : false,
            "currency" => $model->billableServices ? $model->billableServices->currency : BillingService::COP,
            "prices" => $model->networkOperatorClientPrices,
            "client_types" => ClientType::get(),
            "zni_conventional" => count($clientPrices) > 0 ? $clientPrices->where("client_type_id", ClientType::whereType(ClientType::ZIN_CONVENTIONAL)->first()->id)->first()->value : 0,
            "prices_zni_fotovoltaico" => count($clientPrices) > 0 ? $clientPrices->where("client_type_id", ClientType::whereType(ClientType::ZIN_PHOTOVOLTAIC)->first()->id)->first()->value : 0,
            "zni_rural" => count($clientPrices) > 0 ? $clientPrices->where("client_type_id", ClientType::whereType(ClientType::ZIN_RURAL)->first()->id)->first()->value : 0,
            "sin_conventional" => count($clientPrices) > 0 ? $clientPrices->where("client_type_id", ClientType::whereType(ClientType::SIN_CONVENTIONAL)->first()->id)->first()->value : 0,
            "monitoring" => count($clientPrices) > 0 ? $clientPrices->where("client_type_id", ClientType::whereType(ClientType::MONITORING)->first()->id)->first()->value : 0,
            "currencies" => [
                ["key" => "Peso Colombiano", "value" => BillingService::COP],
                ["key" => "Dolar", "value" => BillingService::USD]
            ]
        ]);

    }


    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            foreach ([
                         ClientType::ZIN_CONVENTIONAL => $component->zni_conventional,
                         ClientType::ZIN_PHOTOVOLTAIC => $component->prices_zni_fotovoltaico,
                         ClientType::ZIN_RURAL => $component->zni_rural,
                         ClientType::SIN_CONVENTIONAL => $component->sin_conventional,
                         ClientType::MONITORING => $component->monitoring,
                     ] as $key => $priceClient) {
                $clientTypeId = ClientType::whereType($key)->first()->id;
                if (!$price = $component->model->networkOperatorClientPrices()->where([
                    "client_type_id" => $clientTypeId,
                ])->first()) {
                    $component->model->networkOperatorClientPrices()->create([
                        "client_type_id" => $clientTypeId,
                        "value" => $priceClient
                    ]);
                    continue;
                }
                $price->update([
                    "value" => $priceClient
                ]);
            }
            $component->prices = $component->model->networkOperatorClientPrices;


            if ($billableService = $component->model->billableServices) {
                $billableService->update([
                    "orders_price" => $component->orders_price,
                    "pqr_price" => $component->pqr_price,
                    "currency" => $component->currency,
                    "initial_package_pqr_price" => $component->initial_package_pqr_price,
                    "initial_package_orders_price" => $component->initial_package_orders_price,
                    "min_client_value" => $component->min_client_value,
                    "min_clients" => $component->min_clients,
                    "pqr_initial_bag" => $component->pqr_bag,
                    "work_order_initial_bag" => $component->work_order_hours,
                    "billing_day" => $component->billing_day
                ]);
            } else {
                $component->model->billableServices()->create([
                    "pqr_price" => $component->pqr_price,
                    "orders_price" => $component->orders_price,
                    "currency" => $component->currency,
                    "initial_package_pqr_price" => $component->initial_package_pqr_price,
                    "initial_package_orders_price" => $component->initial_package_orders_price,
                    "min_client_value" => $component->min_client_value,
                    "min_clients" => $component->min_clients,
                    "pqr_initial_bag" => $component->pqr_bag,
                    "work_order_initial_bag" => $component->work_order_hours,
                    "billing_day" => $component->billing_day
                ]);
            }
        });
        ToastEvent::launchToast($component, "show", "success", "Tarifa modificada");

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
