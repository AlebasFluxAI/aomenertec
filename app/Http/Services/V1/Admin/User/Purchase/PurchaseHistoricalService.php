<?php

namespace App\Http\Services\V1\Admin\User\Purchase;

use App\Http\Services\Singleton;
use Livewire\Component;

class PurchaseHistoricalService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->model = $model;
    }

    public function getData(Component $component)
    {
        return $component->model->clientRecharges()->pagination();
    }
}
