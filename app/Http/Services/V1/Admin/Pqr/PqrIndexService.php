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

class PqrIndexService extends Singleton
{
    use PqrStatusTrait;

    public function mount(Component $component, $model)
    {
        $component->model = $model;
    }

    public function getData(Component $component)
    {
        $model = Menu::getUserModel();
        if ($model::class == SuperAdmin::class) {
            return Pqr::paginate();
        }
        if ($model::class == Admin::class) {
            $techniciansUserId = Technician::whereIn("network_operator_id", $model->networkOperators()->pluck("id"))
                ->pluck("user_id");
            return Pqr::whereIn("id", $techniciansUserId)->paginate();
        }
        $user = Auth::user();
        return Pqr::whereIn("id", $user->pqrUsers()->pluck("pqr_id"))->paginate();
    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.peticiones.detalles", ["pqr" => $modelId]);
    }

    public function changeLevel(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        $pqr->update([
            "level" => ($pqr->level == Pqr::PQR_LEVEL_1 ? Pqr::PQR_LEVEL_2 : Pqr::PQR_LEVEL_1)
        ]);
    }
}
