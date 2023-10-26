<?php

namespace App\Observers\WorkOrder;

use App\Models\V1\Support;
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use App\Notifications\Alert\WorkOrderCreatedNotification;
use App\Notifications\Alert\WorkOrderUpdatedNotification;
use Illuminate\Support\Facades\Auth;

class WorkOrderObserver
{
    public function creating(WorkOrder $workOrder)
    {

        $workOrder->status = WorkOrder::WORK_ORDER_STATUS_OPEN;
        if (!$workOrder->created_by_id) {
            $userModel = User::getUserModel();
            $workOrder->created_by_type = User::class;
            $workOrder->created_by_id = $userModel->user_id;
        }
    }

    public function created(WorkOrder $workOrder)
    {
        if ($workOrder->type == WorkOrder::WORK_ORDER_TYPE_DISABLE_CLIENT) {
            $this->notifySupportUsers($workOrder);
            return;
        }
        $user = null;
        if ($workOrder->technician) {
            $user = $workOrder->technician->user;
        }
        if ($workOrder->support) {
            $user = $workOrder->support->user;
        }
        if (!$user) {
            return;
        }
        $user->notify(new WorkOrderCreatedNotification($workOrder));
    }

    private function notifySupportUsers(WorkOrder $workOrder)
    {
        foreach (Support::get() as $support) {
            $support->user->notify(new WorkOrderCreatedNotification($workOrder));
        }
    }

    public function updating(WorkOrder $workOrder)
    {
        if ($workOrder->isDirty("status")) {
            $workOrder->{$workOrder->status . "_at"} = now();
            $workOrder->{$workOrder->status . "_by"} = Auth::user()->id;
        }
    }

    public function updated(WorkOrder $workOrder)
    {

        if ($workOrder->isDirty("status")) {

            if ($workOrder->status == WorkOrder::WORK_ORDER_STATUS_OPEN) {
                return;
            }

            $workOrder->createdBy()->notify(new WorkOrderUpdatedNotification($workOrder));
        }
    }
}
