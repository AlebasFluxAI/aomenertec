<?php

namespace App\Http\Services\V1\Admin\WorkOrder;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use App\Models\V1\RealTimeListener;
use App\Models\V1\Technician;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use PhpMqtt\Client\Facades\MQTT;

class WorkOrderSolverService extends Singleton
{
    use WithPagination;

    public function mount(Component $component, WorkOrder $workOrder)
    {
        $component->fill([
            "model" => $workOrder,
        ]);
    }


    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->validate([
                'evidences.*' => 'image|max:1024', // 1MB Max
            ]);
            foreach ($component->evidences as $evidence) {
                $component->model->saveImageOnModelWithMorphMany($evidence, "evidences");
            }

            $component->model->update([
                "solution_description" => $component->solution_description,
                "status" => WorkOrder::WORK_ORDER_STATUS_CLOSED
            ]);
        });
        $component->redirectRoute("administrar.v1.ordenes_de_servicio.detalle", ["workOrder" => $component->model->id]);
    }
}
