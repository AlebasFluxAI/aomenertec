<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\Traits\PqrTypesTrait;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Pqr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class HistoricalPqrGuestClientService extends Singleton
{

    public function mount(Component $component, $pqr)
    {
        $component->model = $pqr;
    }
}
