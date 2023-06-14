<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Resources\V1\Menu;
use App\Http\Services\Singleton;
use App\Models\Traits\EquipmentAssignationTrait;
use App\Models\Traits\PqrStatusTrait;
use App\Models\V1\Admin;
use App\Models\V1\AdminEquipmentType;
use App\Models\V1\Client;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Pqr;
use App\Models\V1\PqrUser;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Scope\PaginationScope;
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

    public function linkClientConditional(Component $component)
    {
        return $component->model->hasClient();
    }

    public function getData(Component $component)
    {
        $model = Menu::getUserModel();
        if ($model::class == SuperAdmin::class) {
            return Pqr::pagination();
        }
        if ($model::class == NetworkOperator::class) {
            $techniciansUserId = Technician::whereNetworkOperatorId($model->id)
                ->pluck("id");
            return Pqr::whereIn("technician_id", $techniciansUserId)->orWhere("network_operator_id", $model->id)->pagination();
        }
        if ($model::class == Admin::class) {
            $techniciansUserId = Technician::whereIn("network_operator_id", $model->networkOperators()->pluck("id"))
                ->pluck("id");
            return Pqr::whereIn("technician_id", $techniciansUserId)->pagination();
        }
        if ($model::class == Technician::class) {
            return Pqr::where("technician_id", $model->id)->pagination();
        }
        if ($model::class == Supervisor::class) {
            $clientsUserId = Client::whereIn("id", $model->clients->pluck("id"))
                ->pluck("id");
            return Pqr::whereIn("client_id", $clientsUserId)->pagination();
        }

        if ($model::class == Support::class) {
            return $model->pqrs()->pagination();
        }

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
