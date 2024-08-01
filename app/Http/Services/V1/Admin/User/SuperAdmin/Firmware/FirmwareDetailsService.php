<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin\Firmware;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Livewire\Component;

class FirmwareDetailsService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model
        ]);
    }

}
