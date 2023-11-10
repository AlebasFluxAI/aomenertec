<?php

namespace App\Http\Services\V1\Admin\User\Support;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use Livewire\Component;

class IndexWorkOrderService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }

    public function takeWorkOrder(Component $component, $workOrderId)
    {
        $workOrder = WorkOrder::find($workOrderId);
        $workOrder->update([
            "taken" => true,
            "support_id" => User::getUserModel()->id,
        ]);
        ToastEvent::launchToast($component, "show", "success", "Orden de trabajo tomada");
        $workOrder->refresh();
    }

    public function getData(Component $component)
    {
        return WorkOrder::whereTaken(false)->pagination();
    }


}
