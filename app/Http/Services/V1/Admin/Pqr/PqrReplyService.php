<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\Traits\PqrTypesTrait;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Pqr;
use App\Models\V1\PqrMessage;
use App\Models\V1\PqrUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PqrReplyService extends Singleton
{

    use PqrTypesTrait;

    public function mount(Component $component, $model)
    {
        $component->model = $model;
        $messages = $component->messages;
    }

}
