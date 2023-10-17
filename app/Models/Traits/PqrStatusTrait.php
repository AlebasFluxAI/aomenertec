<?php

namespace App\Models\Traits;

use App\Http\Resources\V1\ToastEvent;
use App\Jobs\CreateWorkOrderJob;
use App\Models\V1\Image;
use App\Models\V1\Pqr;
use App\Models\V1\WorkOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

trait PqrStatusTrait
{
    public function openTicked(Component $component, $id)
    {
        if ((Pqr::find($id)->status == Pqr::STATUS_RESOLVED)) {
            return true;
        }
        return (Pqr::find($id)->status == Pqr::STATUS_CLOSED);
    }

    public function resolvedTicked(Component $component, $id)
    {
        return !(Pqr::find($id)->status == Pqr::STATUS_RESOLVED);
    }

    public function closedTicked(Component $component, $id)
    {
        return !(Pqr::find($id)->status == Pqr::STATUS_CLOSED);
    }

    public function processingPqr(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        $pqr->update(
            ["status" => Pqr::STATUS_PROCESSING]
        );
        ToastEvent::launchToast($component, "show", "success", "Solucion de pqr rechazada");
        
    }

    public function closePqr(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        if ($workOrder = $pqr->workOrder) {
            if ($workOrder->staus != WorkOrder::WORK_ORDER_STATUS_CLOSED) {
                ToastEvent::launchToast($component, "show", "error", "Existe una orden de trabajo asociada pendiente");
                return;
            }

        }
        $pqr->update(
            ["status" => Pqr::STATUS_CLOSED]
        );
        $pqr->refresh();
    }

    public function solvePqr(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        if ($workOrder = $pqr->workOrder) {
            if ($workOrder->staus != WorkOrder::WORK_ORDER_STATUS_CLOSED) {
                ToastEvent::launchToast($component, "show", "error", "Existe una orden de trabajo asociada pendiente");
                return false;
            }
        }
        $pqr->update(
            ["status" => Pqr::STATUS_RESOLVED]
        );
        return true;
    }

    public function requestEquipment(Component $component, $id)
    {
        DB::transaction(function () use ($id) {
            $pqr = Pqr::find($id);
            $pqr->update(
                ["change_equipment" => true]
            );
            if ($pqr->technician_id) {
                dispatch(new CreateWorkOrderJob($pqr, Auth::user()->id))->onConnection("sync");
            }
        });
    }

    public function equipmentRequest(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        if ($pqr->has_equipment_changed) {
            return true;
        }
        if (!$pqr->technician_id) {
            return true;
        }
        return !($this->equipmentNotRequest($component, $id));
    }

    public function equipmentNotRequest(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        if (!$pqr->technician_id) {
            return true;
        }
        if ($pqr->status == Pqr::STATUS_CLOSED) {
            return true;
        }
        if ($pqr->has_equipment_changed) {
            return true;
        }
        return ($pqr->change_equipment);
    }
}
