<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Services\Singleton;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ClientHandReadingCreateService extends Singleton
{
    use WithPagination;

    public function mount(Component $component, WorkOrder $order)
    {

        $component->fill([
            "model" => WorkOrder::with('client', 'technician')->find($order->id),
            "model.technician.name" => $order->technician->name . ' ' . $order->technician->last_name
        ]);
    }


    public function rules()
    {
        return [
            'model.id' => 'required',
            'model.status' => 'required',
            'model.microcontroller_data_id' => 'required',
            'model.taken' => 'required',
            'model.closed_by' => 'required',
            'model.in_progress_by' => 'required',
            'model.open_by' => 'required',
            'model.type' => 'required',
            'model.created_at' => 'required',

            'model.microcontrollerData.raw_json' => 'required',
            'model.microcontrollerData.accumulated_real_consumption' => 'required',
            'model.microcontrollerData.accumulated_reactive_consumption' => 'required',
            'model.microcontrollerData.source_timestamp' => 'required',
            'model.microcontrollerData.is_alert' => 'required',
            'model.microcontrollerData.manually' => 'required',

            'model.client.name' => 'required',
            'model.client.code' => 'required',
            'model.client.identification' => 'required',

            'model.technician.name' => 'required',

        ];
    }

    public function submitForm(Component $component)
    {
        $data_frame = config('data-frame.data_frame');
        $json = [];
        $client = $component->model->client;
        $equipment = $client->equipments()->whereEquipmentTypeId(1)->first();
        $date = Carbon::create($component->model->microcontrollerData['source_timestamp']);
        $unix = $date->timestamp;
        foreach ($data_frame as $data) {
            if ($data['start'] >= 450) {
                $json[$data['variable_name']] = 0;
                $json["data_" . $data['variable_name']] = 0;
            } else {
                if ($data['variable_name'] == "flags") {
                    $json[$data['variable_name']] = 0;
                } else {
                    if ($data['variable_name'] == "equipment_id") {
                        $json[$data['variable_name']] = $equipment->serial;
                    } elseif ($data['variable_name'] == "import_wh") {
                        $json[$data['variable_name']] = $component->model->microcontrollerData['accumulated_real_consumption'];
                    } elseif ($data['variable_name'] == "timestamp") {
                        $json[$data['variable_name']] = $unix;
                    } elseif ($data['variable_name'] == "import_VArh") {
                        $json[$data['variable_name']] = $component->model->microcontrollerData['accumulated_reactive_consumption'];
                    } else {
                        $json[$data['variable_name']] = 0;
                    }
                }
            }
            if ($data['variable_name'] == "ph3_varLh_acumm") {
                break;
            }
        }
        $microcontrollerData = null;
        DB::transaction(function () use ($component, $json, &$microcontrollerData) {
            $component->validate([
                'evidences.*' => 'image|max:1024', // 1MB Max
            ]);


            $microcontrollerData = $component->model->microcontrollerData()->create([
                'raw_json' => json_encode($json),
                'accumulated_real_consumption' => $component->model->microcontrollerData['accumulated_real_consumption'],
                'accumulated_reactive_consumption' => $component->model->microcontrollerData['accumulated_reactive_consumption'],
                'source_timestamp' => Carbon::create($component->model->microcontrollerData['source_timestamp'])->format('Y-m-d H:i:s'),
                'manually' => true
            ]);
            foreach ($component->evidences as $evidence) {
                $microcontrollerData->saveImageOnModelWithMorphMany($evidence, "evidences");
            }

            $component->model->update([
                "microcontroller_data_id" => $microcontrollerData->id,
                "solution_description" => 'Lectura tomada',
                "status" => WorkOrder::WORK_ORDER_STATUS_CLOSED,
                "open_at" => Carbon::now()->format('Y-m-d H:i:s'),
                "in_progress_at" => Carbon::now()->format('Y-m-d H:i:s'),
                "closed_at" => Carbon::now()->format('Y-m-d H:i:s')
            ]);
            foreach ($component->evidences as $evidence) {
                $component->model->saveImageOnModelWithMorphMany($evidence, "evidences");
            }
        });
        $component->redirectRoute("v1.admin.client.hand_reading.detalle", ["microcontroller_data" => $microcontrollerData->id]);
    }
}
