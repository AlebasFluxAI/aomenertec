<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use App\Models\V1\NetworkOperator;
use App\Models\V1\RealTimeListener;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use App\Scope\PaginationScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use PhpMqtt\Client\Facades\MQTT;

class WorkOrderClientService extends Singleton
{
    use WithPagination;

    public function mount(Component $component, Client $client)
    {
        $component->model = $client;
        $component->fill([
            "equipmentsBachelor" => $client->equipmentsAsKeyValue(),
            "types" => WorkOrder::getTypeAsKeyValue(),
            "type" => WorkOrder::WORK_ORDER_TYPE_REPLACE,
            "technicians" => $this->getTechnicians($component),
            "supports" => $this->getSupports($component)
        ]);
    }


    public function getSupports($component)
    {
        $component->support_select_disabled = false;
        return Support::get()->map(function ($support) {
            return [
                "key" => $support->id . " - " . $support->name . " - " . $support->identification,
                "value" => $support->id
            ];
        })->toArray();
    }

    public function getTechnicians($component)
    {

        $component->technician_select_disabled = false;
        if (User::getUserModel()::class == SuperAdmin::class) {
            $technicians = Technician::get();
        } else {
            $technicians = Technician::whereIn("network_operator_id",
                NetworkOperator::whereAdminId($component->model->admin_id)->pluck("id"))
                ->get();
        }
        return $technicians->map(function ($technician) {
            return [
                "key" => $technician->id . " - " . $technician->name . " - " . $technician->identification,
                "value" => $technician->id
            ];
        })->toArray();
    }

    public function submitForm(Component $component)
    {
        $component->validate([
            'photos.*' => 'image|max:1024', // 1MB Max
        ]);

        DB::transaction(function () use ($component) {
            $workOrder = $component->model->workOrders()->create($this->mapper($component));
            foreach ($component->photos as $photo) {
                $workOrder->saveImageOnModelWithMorphMany($photo, "images");
            }
            $this->relateEquipment($component, $workOrder);
            $component->redirectRoute("administrar.v1.ordenes_de_servicio.detalle", ["workOrder" => $workOrder->id]);
        });
    }

    private function relateEquipment(Component $component, WorkOrder $workOrder)
    {
        if ($component->equipment_id) {
            $workOrder->equipments()->create([
                "equipment_id" => $component->equipment_id
            ]);
        }
    }

    private function mapper(Component $component)
    {
        return [
            "description" => $component->description,
            "type" => $component->type,
            "technician_id" => $component->technician_id,
            "support_id" => $component->support_id,
            "materials" => $component->materials,
            "tools" => $component->tools,
            "days" => $component->days,
            "hours" => $component->hours,
            "minutes" => $component->minutes,
        ];
    }

    public function conditionalManuallyDetail(Component $component, $modelId)
    {
        return !(($modelId->microcontroller_data_id == null or $modelId->microcontroller_data_id == "") and $modelId->type == WorkOrder::WORK_ORDER_TYPE_READING);
    }
    public function conditionalManuallyCreate(Component $component, $modelId)
    {
        return (($modelId->microcontroller_data_id == null or $modelId->microcontroller_data_id == "") and $modelId->type == WorkOrder::WORK_ORDER_TYPE_READING);
    }
    public function getData(Component $component)
    {
        return $component->model->workOrders()->pagination();
    }
}
