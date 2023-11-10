<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Services\Singleton;
use App\Models\V1\Client;
use App\Models\V1\NetworkOperator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NetworkOperatorIndexService extends Singleton
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
        $admin = $user->admin;
        if ($admin) {
            if ($component->filter) {
                return $admin->networkOperators()->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
            }
            return $admin->networkOperators()->pagination();
        }
        if ($component->filter) {
            return NetworkOperator::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }
        return NetworkOperator::pagination();
    }

    public function deleteNetworkOperator(Component $component, $networkOperatorId)
    {
        $operator = NetworkOperator::find($networkOperatorId);
        foreach ($operator->equipments()->get() as $type) {
            $type->network_operator_id = null;
            $type->save();
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$operator->name} eliminado"]);
        $operator->delete();
    }

    public function disableNetworkOperator(Component $component, $modelId)
    {
        $operator = NetworkOperator::find($modelId);
        $operator->enabled = !$operator->enabled;
        $operator->user->enabled = !$operator->user->enabled;
        $operator->push();
        if (!$operator->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function getEnabledNetworkOperator(Component $component, $modelId)
    {
        return !NetworkOperator::find($modelId)->enabled;
    }

    public function getEnabledAuxNetworkOperator(Component $component, $modelId)
    {
        if (!NetworkOperator::find($modelId)->enabled) {
            return false;
        }
        return true;
    }

    public function conditionalDeleteNetworkOperator(Component $component, $modelId)
    {
        return Client::whereNetworkOperatorId($modelId)->exists();
    }

    public function conditionalLinkEquipmentNetworkOperator(Component $component, $modelId)
    {
        return !NetworkOperator::find($modelId)->admin->equipments()->exists();
    }
}
