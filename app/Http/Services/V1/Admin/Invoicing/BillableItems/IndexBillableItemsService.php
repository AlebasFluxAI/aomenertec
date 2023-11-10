<?php

namespace App\Http\Services\V1\Admin\Invoicing\BillableItems;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\BillableItem;
use Livewire\Component;

class IndexBillableItemsService extends Singleton
{

    public function getData(Component $component)
    {
        if ($component->filter) {
            return BillableItem::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }
        return BillableItem::pagination();
    }


}
