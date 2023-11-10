<?php

namespace App\Http\Services\V1\Admin\Invoicing\BillableItems;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\BillableItem;
use Livewire\Component;

class DetailsBillableItemsService extends Singleton
{
    public function mount(Component $component, BillableItem $billableItem)
    {
        $component->fill([
            "model" => $billableItem
        ]);
    }

}
