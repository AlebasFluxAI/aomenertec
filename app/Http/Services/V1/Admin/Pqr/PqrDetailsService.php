<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Pqr;
use App\Models\V1\PqrUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PqrDetailsService extends Singleton
{
    public function getData(Component $component)
    {
        $user = Auth::user();

        return Pqr::whereIn("id", $user->pqrUsers()->pluck("pqr_id"))->pagination();
    }

    public function changeLevel(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        $pqr->update([
            "level" => ($pqr->level == Pqr::PQR_LEVEL_1 ? Pqr::PQR_LEVEL_2 : Pqr::PQR_LEVEL_1)
        ]);
    }
}
