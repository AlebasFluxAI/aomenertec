<?php

namespace App\Http\Services\V1\Admin\Pqr;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Traits\PqrStatusTrait;
use App\Models\V1\Pqr;
use Livewire\Component;

class HistoricalPqrGuestClientService extends Singleton
{
    use PqrStatusTrait;

    public function mount(Component $component, Pqr $pqr)
    {
        $component->model = $pqr;
    }

    public function closePqrForm(Component $component)
    {
        $this->closePqr($component, $component->model->id);
        ToastEvent::launchToast($component, "show", "success", "Pqr cerrado exitosamente");
        $component->model->refresh();
    }

    public function rejectPqrForm(Component $component)
    {
        $this->processingPqr($component, $component->model->id);
        $component->model->refresh();
    }

}
