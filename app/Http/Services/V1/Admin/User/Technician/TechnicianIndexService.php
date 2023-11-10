<?php

namespace App\Http\Services\V1\Admin\User\Technician;

use App\Http\Services\Singleton;
use App\Models\V1\Technician;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TechnicianIndexService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }

    public function getData(Component $component)
    {
        $user = Auth::user();

        if ($networkOperator = $user->networkOperator) {
            if ($component->filter) {
                return $networkOperator->technicians()->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
            }
            return $networkOperator->technicians()->pagination();
        }

        if ($admin = $user->admin) {
            if ($component->filter) {
                return Technician::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
            }
            return Technician::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))->pagination();
        }


        if ($component->filter) {
            return Technician::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }
        return Technician::pagination();
    }

    public function deleteTechnician(Component $component, $technicianId)
    {
        $technician = Technician::find($technicianId);
        $technician->user->enabled = false;
        foreach ($technician->equipments()->get() as $type) {
            $type->technician_id = "";
            $type->save();
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$technician->name} eliminado"]);
        $technician->delete();
    }

    public function disableTechnician(Component $component, $modelId)
    {
        $technician = Technician::find($modelId);
        $technician->enabled = !$technician->enabled;
        $technician->user->enabled = !$technician->user->enabled;
        $technician->push();
        if (!$technician->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function getEnabledTechnician(Component $component, $modelId)
    {
        return !Technician::find($modelId)->enabled;
    }

    public function getEnabledAuxTechnician(Component $component, $modelId)
    {
        if (!Technician::find($modelId)->enabled) {
            return false;
        }
        return true;
    }

    public function conditionalDeleteTechnician(Component $component, $modelId)
    {
        return Technician::find($modelId)->clientTechnicians()->exists();
    }

    public function conditionalLinkEquipmentTechnician(Component $component, $modelId)
    {
        if (!Technician::find($modelId)->networkOperator) {
            return true;
        }
        return !Technician::find($modelId)->networkOperator->equipments()->exists();
    }

    public function conditionalLinkClientsTechnician(Component $component, $modelId)
    {
        try {
            return !Technician::find($modelId)->networkOperator->clients()->exists();
        } catch (\Throwable) {
            return true;
        }
    }
}
