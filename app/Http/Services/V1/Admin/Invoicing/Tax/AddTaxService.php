<?php

namespace App\Http\Services\V1\Admin\Invoicing\Tax;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Tax;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AddTaxService extends Singleton
{

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $tax = Tax::create([
                "name" => $component->name,
                "description" => $component->description,
                "percentage" => $component->percentage,
            ]);
            $component->redirectRoute("administrar.v1.facturacion.impuestos.detalle", ["tax" => $tax->id]);
        }
        );


    }

}
