<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Resources\V1\MonthsYears;
use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\AdminClientType;
use App\Models\V1\AdminConfiguration;
use App\Models\V1\AdminPrice;
use App\Models\V1\AvailableChannel;
use App\Models\V1\ClientType;
use App\Models\V1\TabPermissionAdmin;
use Livewire\Component;

class PriceAdminService extends Singleton
{
    public function mount(Component $component, Admin $model)
    {
        $component->channels = $model->channels;
        if (!$model->priceAdmin()->exists()) {
            if ($model->adminClientTypes()->exists()) {
                foreach ($model->adminClientTypes as $type) {
                    AdminPrice::create([
                        "admin_id" => $model->id,
                        "client_type_id" => $type->client_type_id,
                        "value" => 0,
                    ]);
                }
            }
        }
        if (!$model->configAdmin()->exists()) {
            AdminConfiguration::create([
                "admin_id" => $model->id,
                "min_value" => 0,
                "min_clients" => 10,
            ]);
        }

        $component->fill([
            "client_types" => ClientType::all(),
            "model" => $model,
            'months' => MonthsYears::months(),
            "invoicing_day" => $model->invoicing_day,
            "prices" => $model->priceAdmin,
            "config" => $model->configAdmin,
            "annually_client_cost" => $model->annually_client_cost,
            "annually_client_invoicing_month" => $model->annually_client_invoicing_month,
            "coins" => [
                ["key" => "Peso Colombiano", "value" => AdminConfiguration::COP],
                ["key" => "Dolar", "value" => AdminConfiguration::USD]
            ],
            "notification_types" => [
                ["key" => "Mensaje de texto", "value" => 1],
                ["key" => "WhatsApp", "value" => 2],
                ["key" => "Email", "value" => 3]
            ],
            "tab_permissions" => $model->tabPermissions,
            "admin_client_types" => $model->adminClientTypes->pluck('client_type_id')->toArray()
        ]);
    }

    public function submitFormPrice(Component $component)
    {
        $component->validate();
        foreach ($component->prices as $price) {
            $price->save();
        }
        $component->config->save();
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Datos actualizados"]);
    }

    public function blinkChannel(Component $component, $channel)
    {
        AvailableChannel::find($channel)->blink();
        $component->channels = $component->model->refresh()->channels;
    }


    public function blinkTabPermission(Component $component, $tabPermission)
    {
        $tabPermissionAdmin = TabPermissionAdmin::find($tabPermission);
        $tabPermissionAdmin->blinkPermission();
        $component->tab_permissions = $component->model->refresh()->tabPermissions;
    }

    public function submitFormConfiguration(Component $component)
    {
        $client_types = $component->model->adminClientTypes;
        foreach ($client_types as $item) {
            if (!in_array($item->client_type_id, $component->admin_client_types)) {
                $component->model->priceAdmin()->whereClientTypeId($item->client_type_id)->first()
                    ->delete();
                $item->delete();
            }
        }
        foreach ($component->admin_client_types as $type) {
            if (!$component->model->adminClientTypes()->whereClientTypeId($type)->exists()) {
                AdminClientType::create(
                    ['admin_id' => $component->model->id,
                        'client_type_id' => $type]
                );
                AdminPrice::create([
                    "admin_id" => $component->model->id,
                    "client_type_id" => $type,
                    "value" => 0,
                ]);
            }
        }
        //$component->config->frame_type = $component->frame_type;
        $component->config->save();
        $component->prices = $component->model->priceAdmin()->get();
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Datos actualizados"]);
    }

    public function submitFormInvoicing(Component $component)
    {
        $component->model->update([
            "invoicing_day" => $component->invoicing_day
        ]);
        $component->model->refresh();
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Datos actualizados"]);
    }

    public function submitAnnuallyForm(Component $component)
    {
        $component->model->update([
            "annually_client_cost" => $component->annually_client_cost,
            "annually_client_invoicing_month" => $component->annually_client_invoicing_month

        ]);
        $component->model->refresh();
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Datos actualizados"]);
    }

}
