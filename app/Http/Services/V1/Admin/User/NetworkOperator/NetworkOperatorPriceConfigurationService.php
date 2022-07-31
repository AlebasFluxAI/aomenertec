<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Services\Singleton;
use App\Models\Traits\NetworkOperatorPriceTrait;
use App\Models\V1\Client;
use App\Models\V1\NetworkOperator;
use App\Models\V1\PhotovoltaicPrice;
use App\Models\V1\Stratum;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NetworkOperatorPriceConfigurationService extends Singleton
{
    use NetworkOperatorPriceTrait;

    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function getData(Component $component)
    {
        return Stratum::get();
    }
}
