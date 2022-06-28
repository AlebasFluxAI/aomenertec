<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Livewire\V1\Admin\User\Admin\PriceAdmin;
use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\AdminConfiguration;
use App\Models\V1\AdminPrice;
use App\Models\V1\ClientType;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Livewire\Component;

class PriceAdminService extends Singleton
{
    public function mount(Component $component, Admin $model)
    {

        if (!$model->priceAdmin()->exists()) {
            foreach (ClientType::all() as $type) {
                AdminPrice::create([
                    "admin_id" => $model->id,
                    "client_type_id" => $type->id,
                    "value" => 0,
                ]);
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
            "prices"=> $model->priceAdmin,
            "config"=> $model->configAdmin,
            "coins" => [
                        ["key" => "Peso Colombiano", "value" => AdminConfiguration::COP],
                        ["key" => "Dolar", "value" => AdminConfiguration::USD]
                       ],
        ]);

    }

    public function submitForm(Component $component)
    {
        $component->validate();
        foreach ($component->prices as $price) {
            $price->save();
        }
        $component->config->save();
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "Datos actualizados"]);

    }



}
