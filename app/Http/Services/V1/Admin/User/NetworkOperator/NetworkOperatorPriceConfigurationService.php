<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Resources\V1\MonthsYears;
use App\Http\Services\Singleton;
use App\Models\Traits\NetworkOperatorPriceTrait;
use App\Models\V1\Stratum;
use Livewire\Component;

class NetworkOperatorPriceConfigurationService extends Singleton
{
    use NetworkOperatorPriceTrait;

    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
            'months' => MonthsYears::months(),
            'years' => MonthsYears::years(),
            "date_picked" => false
        ]);
    }


    public function getData(Component $component)
    {
        return Stratum::get();
    }
}
