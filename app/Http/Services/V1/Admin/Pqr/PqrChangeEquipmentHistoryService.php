<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Resources\V1\Menu;
use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\Traits\PqrStatusTrait;
use App\Models\V1\Admin;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Pqr;
use App\Models\V1\PqrUser;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Technician;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PqrChangeEquipmentHistoryService extends Singleton
{
    use PqrStatusTrait;

    public function mount(Component $component, $model)
    {
        $component->model = $model;
        
    }

}
