<?php

namespace App\Observers\BillableItem;

use App\Models\V1\BillableItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class BillableItemObserver
{
    /**
     * Handle the "created" event.
     *
     * @param mixed $models
     */
    public function creating(BillableItem $billableItem)
    {
        $billableItem->code = "BI-" . BillableItem::count() + 1;
    }

}
