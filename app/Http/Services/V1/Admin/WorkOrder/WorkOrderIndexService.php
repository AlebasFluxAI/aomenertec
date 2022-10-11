<?php

namespace App\Http\Services\V1\Admin\WorkOrder;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use App\Models\V1\NetworkOperator;
use App\Models\V1\RealTimeListener;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use PhpMqtt\Client\Facades\MQTT;

class WorkOrderIndexService extends Singleton
{
    use WithPagination;


    public function setInProgressWorkOrderConditional(Component $component, $workOrderId)
    {
        return (WorkOrder::find($workOrderId)->status == WorkOrder::WORK_ORDER_STATUS_IN_PROGRESS);
    }


    public function processEquipmentReplace(Component $component, $workOrderId)
    {
        $workOrder = WorkOrder::find($workOrderId);
        $component->redirectRoute("administrar.v1.peticiones.cambio-equipo", ["pqr" => $workOrder->pqr_id]);
    }


    public function setOpenWorkOrderConditional(Component $component, $workOrderId)
    {
        return (WorkOrder::find($workOrderId)->status == WorkOrder::WORK_ORDER_STATUS_OPEN);
    }

    public function replaceEquipmentHandlerConditional(Component $component, $workOrderId)
    {
        $workOrder = WorkOrder::find($workOrderId);
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
        if ($userModel::class == Technician::class) {
            return $userModel->workOrders;
        }

        if ($userModel::class == NetworkOperator::class) {
            $clientId = Client::whereAdminId($userModel->admin_id)
                ->pluck("id");
            return WorkOrder::whereIn("client_id", $clientId)->paginate();
        }
        if ($userModel::class == Admin::class) {
            $clientId = Client::whereAdminId($userModel->id)
                ->pluck("id");
            return WorkOrder::whereIn("client_id", $clientId)->paginate();
        }
        return WorkOrder::paginate();
    }

    public function setInProgress($workOrderId)
    {
        WorkOrder::find($workOrderId)->setInProgress();
    }

    public function setOpen($workOrderId)
    {
        WorkOrder::find($workOrderId)->setOpen();
    }
}
