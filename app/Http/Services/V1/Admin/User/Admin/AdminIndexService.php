<?php

namespace App\Http\Services\V1\Admin\User\Admin;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use Livewire\Component;

class AdminIndexService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function edit(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.admin.editar", ["admin" => $modelId]);
    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.admin.detalles", ["admin" => $modelId]);
    }

    public function deleteAdmin(Component $component, $modelId)
    {
        $admin = Admin::find($modelId);
        $admin->user->enabled = false;
        foreach ($admin->adminClientTypes()->get() as $type) {
            $type->delete();
        }
        foreach ($admin->adminClientTypes()->get() as $type) {
            $type->delete();
        }
        foreach ($admin->adminEquipmentTypes()->get() as $type) {
            $type->delete();
        }
        if ($admin->configAdmin()->exists()) {
            $admin->configAdmin()->delete();
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$admin->name} eliminado"]);
        $admin->delete();
    }

    public function disableAdmin(Component $component, $modelId)
    {
        $admin = Admin::find($modelId);
        $admin->enabled = !$admin->enabled;
        $admin->user->enabled = !$admin->user->enabled;
        $admin->push();
        if (!$admin->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);
        }
    }

    public function getEnabledAdmin(Component $component, $modelId)
    {
        return !Admin::find($modelId)->enabled;
    }

    public function getEnabledAuxAdmin(Component $component, $modelId)
    {
        if (!Admin::find($modelId)->enabled) {
            return false;
        }
        return true;
    }

    public function getData(Component $component)
    {
        if ($component->filter) {
            return Admin::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }
        return Admin::pagination();
    }

    public function conditionalDeleteAdmin(Component $component, $modelId)
    {
        return NetworkOperator::whereAdminId($modelId)->exists();
    }
}
