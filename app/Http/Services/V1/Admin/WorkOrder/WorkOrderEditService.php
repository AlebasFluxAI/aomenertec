<?php

namespace App\Http\Services\V1\Admin\WorkOrder;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
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

class WorkOrderEditService extends Singleton
{
    use WithPagination;

    public function mount(Component $component, WorkOrder $workOrder)
    {
        $component->fill([
            "model" => $workOrder,
            "technician_id" => $component->technician_id,
            "description" => $component->description,
            "type" => $component->type,
            "types" => WorkOrder::getTypeAsKeyValue(),
            "technicians" => $this->getTechnicians($component),
        ]);
    }

    public function getTechnicians($component)
    {
        $component->technician_select_disabled = false;
        return Technician::get()->map(function ($technician) {
            return [
                "key" => $technician->id . " - " . $technician->name . " - " . $technician->identification,
                "value" => $technician->id
            ];
        })->toArray();
    }

    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->model->update([
                "technician_id" => $component->technician_id,
                "description" => $component->description,
                "type" => $component->description
            ]);
            $component->redirectRoute("administrar.v1.ordenes_de_servicio.detalle", ["workOrder" => $component->model->id]);
        });
    }

}


