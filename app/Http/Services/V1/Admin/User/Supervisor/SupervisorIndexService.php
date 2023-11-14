<?php

namespace App\Http\Services\V1\Admin\User\Supervisor;

use App\Http\Services\Singleton;
use App\Models\V1\Supervisor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SupervisorIndexService extends Singleton
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
                return $networkOperator->supervisors()->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
            }
            return $networkOperator->supervisors()->pagination();
        }

        if ($admin = $user->admin) {
            if ($component->filter) {
                return Supervisor::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))
                    ->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
            }
            return Supervisor::whereIn('network_operator_id', $admin->networkOperators()->pluck('id'))->pagination();
        }


        if ($component->filter) {
            return Supervisor::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }
        return Supervisor::pagination();
    }

    public function deleteSupervisor(Component $component, $supervisorId)
    {
        $supervisor = Supervisor::find($supervisorId);
        $supervisor->user->enabled = false;
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$supervisor->name} eliminado"]);
        $supervisor->delete();
    }

    public function disableSupervisor(Component $component, $modelId)
    {
        $supervisor = Supervisor::find($modelId);
        $supervisor->enabled = !$supervisor->enabled;
        $supervisor->user->enabled = !$supervisor->user->enabled;
        $supervisor->push();
        if (!$supervisor->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function getEnabledSupervisor(Component $component, $modelId)
    {
        return !Supervisor::find($modelId)->enabled;
    }

    public function getEnabledAuxSupervisor(Component $component, $modelId)
    {
        if (!Supervisor::find($modelId)->enabled) {
            return false;
        }
        return true;
    }

    public function conditionalDeleteSupervisor(Component $component, $modelId)
    {
        return Supervisor::find($modelId)->clientSupervisors()->exists();
    }

    public function conditionalLinkClientsSupervisor(Component $component, $modelId)
    {
        return !Supervisor::find($modelId)->networkOperator->clients()->exists();
    }
}
