<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use Livewire\Component;

class AdminDetailsService extends Singleton
{
    public function mount(Component $component, $admin)
    {

        $component->fill([
            'admin' => $admin,
        ]);
    }

}
