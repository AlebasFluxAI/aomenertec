<?php

namespace App\Http\Services\V1\Admin\Invoicing\Tax;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Tax;
use Livewire\Component;

class DetailsTaxService extends Singleton
{
    public function mount(Component $component, Tax $tax)
    {
        $component->fill([
            "model" => $tax
        ]);
    }

}
