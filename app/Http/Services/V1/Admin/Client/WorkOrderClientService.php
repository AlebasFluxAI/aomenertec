<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class WorkOrderClientService extends Singleton
{
    use WithPagination;

    public function changeTool(Component $component, $value)
    {
        $this->validateArray($component->toolsListSelected, $value);
        $this->validateOthers($component);
    }

    private function validateArray(&$arraySelected, $value)
    {
        $key = array_search($value, $arraySelected);

        if ($key !== false) {
            unset($arraySelected[$key]);

        } else {
            $arraySelected[] = $value;
        }
    }

    private function validateOthers(Component $component)
    {
        if (in_array("Otras", $component->toolsListSelected)) {
            $component->otherTool = true;
        } else {
            $component->otherTool = false;
        }

        if (in_array("Otros", $component->materialsListSelected)) {
            $component->otherMaterials = true;
        } else {
            $component->otherMaterials = false;
        }
    }

    public function changeMaterials(Component $component, $value)
    {
        $this->validateArray($component->materialsListSelected, $value);
        $this->validateOthers($component);
    }

    public function changeEquipment(Component $component, $value)
    {
        $this->validateArray($component->equipmentsListSelected, $value);
    }

    public function mount(Component $component, Client $client)
    {
        $component->model = $client;
        $component->fill([
            "equipmentsBachelor" => $client->equipmentsAsKeyValue(),
            "types" => WorkOrder::getTypeAsKeyValue(),
            "type" => WorkOrder::WORK_ORDER_TYPE_REPLACE,
            "technicians" => $this->getTechnicians($component),
            "supports" => $this->getSupports($component),
            "toolsList" => WorkOrder::getWorkOrderTools(),
            "materialsList" => WorkOrder::getWorkOrderMaterials(),
            "otherTool" => false,
            "images" => ["image1", "image2", "image3", "image4"],
        ]);
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

    public function submitForm(Component $component)
    {


        DB::transaction(function () use ($component) {
            $workOrder = $component->model->workOrders()->create($this->mapper($component));
            foreach ($component->images as $key => $image) {
                if (!$component->{$image}) {
                    continue;
                }
                $workOrder->saveImageOnModelWithMorphMany($component->{$image}, "images", $component->{"description" . $key + 1});
            }
            $this->relateEquipment($component, $workOrder);
            $component->redirectRoute("administrar.v1.ordenes_de_servicio.detalle", ["workOrder" => $workOrder->id]);
        });
    }

    private function mapper(Component $component)
    {
        return [
            "description" => $component->description,
            "type" => $component->type,
            "technician_id" => $component->technician_id,
            "support_id" => $component->support_id,
            "materials" => $component->materials . "," . implode(", ", $component->materialsListSelected),
            "tools" => $component->tools . "," . implode(", ", $component->toolsListSelected),
            "days" => $component->days,
            "hours" => $component->hours,
            "minutes" => $component->minutes,
        ];
    }

    private function relateEquipment(Component $component, WorkOrder $workOrder)
    {
        foreach ($component->equipmentsListSelected as $equipment) {
            $workOrder->equipments()->create([
                "equipment_id" => $equipment
            ]);
        }
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

    public function conditionalManuallyDetail(Component $component, $modelId)
    {
        return !(($modelId->microcontroller_data_id == null or $modelId->microcontroller_data_id == "") and $modelId->type == WorkOrder::WORK_ORDER_TYPE_READING);
    }

    public function conditionalManuallyCreate(Component $component, $modelId)
    {
        return false;
    }

    public function getData(Component $component)
    {
        return $component->model->workOrders()->pagination();
    }
}
