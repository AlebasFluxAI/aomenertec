<?php

namespace App\Http\Services\V1\Admin\Invoicing\Tax;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Tax;
use Livewire\Component;

class IndexTaxService extends Singleton
{

    public function getData(Component $component)
    {
        if ($component->filter) {
            return Tax::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }
        return Tax::pagination();
    }


}
