<?php

namespace App\Models\Traits;

use App\Models\V1\Image;
use App\Models\V1\Pqr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
            ["status" => Pqr::STATUS_CLOSED]);
    }

    public function solvePqr(Component $component, $id)
    {
        Pqr::find($id)->update(
            ["status" => Pqr::STATUS_RESOLVED]);
    }

    public function requestEquipment(Component $component, $id)
    {
        Pqr::find($id)->update(
            ["change_equipment" => true]);
    }


    public function equipmentNotRequest(Component $component, $id)
    {
        $pqr = Pqr::find($id);
        return $pqr->change_equipment;

    }


}
