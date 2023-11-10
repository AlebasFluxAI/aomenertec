<?php

namespace App\Observers\BillableItem;

use App\Models\V1\BillableItem;

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
