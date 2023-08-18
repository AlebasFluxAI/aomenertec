<?php

namespace App\Observers\V1;

class FeeObserver
{
    public function updating($model)
    {
        $model->unit_cost = $model->getTotal();
    }

    public function creating($model)
    {
        $model->unit_cost = $model->getTotal();
    }
}
