<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Resources\V1\Menu;
use App\Http\Resources\V1\ToastEvent;
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
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use App\Scope\PaginationScope;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function canDownloadReport(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        return !($pqr->status == Pqr::STATUS_CLOSED);
    }
    public function downloadReport(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        $pqr_messages_file = $pqr->messagesFile();
        $network_operator = $pqr->client->networkOperator;
        $pdf = PDF::loadView('reports.pqr_report',[
            'pqr' => $pqr,
            'client' => $pqr->client,
            'network_operator' => $network_operator,
            'admin' => $network_operator->admin,
            'files' => $pqr_messages_file,
            'created_by' => $pqr->status_created_by == null ? $pqr->client : User::find($pqr->status_created_by),
            'closed_by' => $pqr->status_closed_by == null ? $pqr->client : User::find($pqr->status_closed_by),
            'resolved_by' => $pqr->status_resolved_by == null ? $pqr->client->clientTechnician()->first() : User::find($pqr->status_resolved_by),
            'close_message' => $pqr->closeMessage
        ]);
        $pdf->setPaper('A4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Pqr_report_'.$pqr->code.'.pdf');
    }

    public function canConvertToOrder(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        if ($pqr->workOrder) {
            return true;
        }
        if (!$pqr->client) {
            return true;
        }
        return !$pqr->level == Pqr::PQR_LEVEL_2;

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

    public function convertToWorkOrder(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        $workOrder = WorkOrder::createFromPqr($pqr);
        ToastEvent::launchToast($component, "show", "success", "Orden de trabajo creada exitosamente");
        $component->redirectRoute("administrar.v1.ordenes_de_servicio.detalle", ["workOrder" => $workOrder->id]);
    }
}
