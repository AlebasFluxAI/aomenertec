<?php

namespace App\Models\Traits;

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
        return (Pqr::find($id)->status == Pqr::STATUS_CLOSED);
    }

    public function closedTicked(Component $component, $id)
    {
        return !(Pqr::find($id)->status == Pqr::STATUS_CLOSED);
    }

    public function closePqr(Component $component, $id)
    {
        Pqr::find($id)->update(
            ["status" => Pqr::STATUS_CLOSED]
        );
    }

    public function solvePqr(Component $component, $id)
    {
        Pqr::find($id)->update(
            ["status" => Pqr::STATUS_RESOLVED]
        );
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
