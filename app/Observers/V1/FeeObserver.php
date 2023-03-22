<?php

namespace App\Observers\V1;

class FeeObserver
{
    public function updating($model)
    {
        $model->total_fee = $model->getTotal();
    }

    public function creating($model)
    {
        $model->total_fee = $model->getTotal();
    }
}
