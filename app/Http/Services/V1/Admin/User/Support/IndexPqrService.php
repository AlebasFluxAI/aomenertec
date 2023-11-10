<?php

namespace App\Http\Services\V1\Admin\User\Support;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\V1\Pqr;
use App\Models\V1\User;
use Livewire\Component;

class IndexPqrService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }

    public function takePqr(Component $component, $workOrderId)
    {
        $workOrder = Pqr::find($workOrderId);
        $workOrder->update([
            "taken" => true,
            "support_id" => User::getUserModel()->id,
        ]);
        ToastEvent::launchToast($component, "show", "success", "PQR tomada");
        $workOrder->refresh();
    }

    public function getData(Component $component)
    {
        return Pqr::whereTaken(false)->where("status", "!=", Pqr::STATUS_CLOSED)->whereLevel(Pqr::PQR_LEVEL_2)->pagination();
    }


}
