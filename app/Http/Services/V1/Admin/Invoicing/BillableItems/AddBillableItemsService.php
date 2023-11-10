<?php

namespace App\Http\Services\V1\Admin\Invoicing\BillableItems;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\BillableItem;
use App\Models\V1\Tax;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AddBillableItemsService extends Singleton
{
    public function mount(Component $component)
    {
        $component->taxes = Tax::taxesAsKeyValue();
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $billableItem = BillableItem::create([
                "name" => $component->name,
                "description" => $component->description,
                "tax_id" => $component->tax_id
            ]);
            $component->redirectRoute("administrar.v1.facturacion.items.detalle", ["billable_item" => $billableItem->id]);
        }
        );


    }

}
