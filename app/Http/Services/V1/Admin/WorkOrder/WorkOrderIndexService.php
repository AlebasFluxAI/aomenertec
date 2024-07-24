<?php

namespace App\Http\Services\V1\Admin\WorkOrder;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use App\Scope\ClientEnabledScope;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;

class WorkOrderIndexService extends Singleton
{
    use WithPagination;


    public function setInProgressWorkOrderConditional(Component $component, $workOrderId)
    {
        return (WorkOrder::find($workOrderId)->status == WorkOrder::WORK_ORDER_STATUS_IN_PROGRESS) or (WorkOrder::find($workOrderId)->status == WorkOrder::WORK_ORDER_STATUS_CLOSED);
    }

    public function canDownloadReport(Component $component, $id)
    {
        $pqr = WorkOrder::find($id);
        return !($pqr->status == WorkOrder::WORK_ORDER_STATUS_CLOSED);
    }

    public function downloadReport(Component $component, $id)
    {
        $work_order = WorkOrder::find($id);
        $network_operator = $work_order->client->networkOperator;
        $pdf = PDF::loadView('reports.orden_work_report', [
            'work_order' => $work_order,
            'client' => $work_order->client,
            'network_operator' => $network_operator,
            'admin' => $network_operator->admin
        ]);
        $pdf->setPaper('A4', 'portrait');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'work_order_report_' . $work_order->id . '.pdf');
    }

    public function adminWorkOrderConditional(Component $component, $workOrderId)
    {
        $workOrder = WorkOrder::find($workOrderId);
        return !($workOrder->status == WorkOrder::WORK_ORDER_STATUS_OPEN and $workOrder->type != WorkOrder::WORK_ORDER_TYPE_READING);
    }

    public function processEquipmentReplace(Component $component, $workOrderId)
    {
        $workOrder = WorkOrder::find($workOrderId);
        $component->redirectRoute("administrar.v1.peticiones.cambio-equipo", ["pqr" => $workOrder->pqr_id]);
    }


    public function setOpenWorkOrderConditional(Component $component, $workOrderId)
    {
        $workOrder = WorkOrder::find($workOrderId);
        if ($workOrder->type == WorkOrder::WORK_ORDER_TYPE_READING) {
            return true;
        }
        return (WorkOrder::find($workOrderId)->status == WorkOrder::WORK_ORDER_STATUS_OPEN);
    }


    public function replaceEquipmentHandlerConditional(Component $component, $workOrderId)
    {
        $workOrder = WorkOrder::find($workOrderId);
        if ($workOrder->type == WorkOrder::WORK_ORDER_TYPE_READING) {
            return true;
        }
        if (!($workOrder->type == WorkOrder::WORK_ORDER_TYPE_REPLACE)) {
            return true;
        }
        if (!($workOrder->pqr_id)) {
            return true;
        }
        return (!($workOrder->status == WorkOrder::WORK_ORDER_STATUS_IN_PROGRESS));
    }


    public function getData()
    {
        $userModel = User::getUserModel();

        if ($userModel::class == NetworkOperator::class) {
            return WorkOrder::where("type", "!=", WorkOrder::WORK_ORDER_TYPE_DISABLE_CLIENT)
                ->where("type", "!=", WorkOrder::WORK_ORDER_TYPE_ENABLE_CLIENT)
                ->whereIn("client_id", $userModel->allClients->pluck("id"))->pagination();
        }
        if ($userModel::class == Admin::class) {
            $clientId = Client::withoutGlobalScope(ClientEnabledScope::class)->whereAdminId($userModel->id)
                ->pluck("id");
            return WorkOrder::whereIn("client_id", $clientId)->pagination();
        }
        if ($userModel::class == Technician::class) {
            $technician = User::getUserModel();
            $clientId = $technician->clientTechnicians->pluck("client_id");
            return WorkOrder::whereIn("client_id", $clientId)->where("type", "!=", WorkOrder::WORK_ORDER_TYPE_DISABLE_CLIENT)->pagination();
        }

        if ($userModel::class == Support::class) {

            return $userModel->workOrders()->pagination();
        }
        return WorkOrder::pagination();
    }

    public function setInProgress($workOrderId)
    {
        WorkOrder::find($workOrderId)->setInProgress();
    }

    public function setOpen($workOrderId)
    {
        WorkOrder::find($workOrderId)->setOpen();
    }

    public function conditionalManuallyDetail(Component $component, $modelId)
    {
        $workOrder = WorkOrder::find($modelId);
        if ($workOrder->type == WorkOrder::WORK_ORDER_TYPE_READING) {
            return ($workOrder->microcontroller_data_id == null || $workOrder->microcontroller_data_id == "");
        }
        return true;
    }

    public function conditionalManuallyCreate(Component $component, $modelId)
    {
        $workOrder = WorkOrder::find($modelId);
        if ($workOrder->type == WorkOrder::WORK_ORDER_TYPE_READING) {
            return !($workOrder->microcontroller_data_id == null || $workOrder->microcontroller_data_id == "");
        }
        return true;
    }

    public function conditionalTypeReading(Component $component, $modelId)
    {
        $workOrder = WorkOrder::find($modelId);
        return $workOrder->type == WorkOrder::WORK_ORDER_TYPE_READING;
    }

    public function conditionalStart(Component $component, $modelId)
    {
        $workOrder = WorkOrder::find($modelId);
        if ($workOrder->status == WorkOrder::WORK_ORDER_STATUS_CLOSED) {
            return true;
        }
        return $workOrder->status == WorkOrder::WORK_ORDER_STATUS_IN_PROGRESS;
    }


}
