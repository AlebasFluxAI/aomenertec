<?php

namespace App\Http\Services\V1\Admin\Invoicing\Tax;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Tax;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EditTaxService extends Singleton
{
    public function mount(Component $component, Tax $tax)
    {
        $component->fill([
            "model" => $tax,
            "name" => $tax->name,
            "description" => $tax->description,
            "percentage" => $tax->percentage,
        ]);
    }


    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->model->update([
                "name" => $component->name,
                "description" => $component->description,
                "percentage" => $component->percentage,
            ]);
            $component->redirectRoute("administrar.v1.facturacion.impuestos.detalle", ["tax" => $component->model->id]);
        }
        );


    }

}
