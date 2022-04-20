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

    public function delete(Component $component, $modelId)
    {
        $admin = Admin::find($modelId);
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$admin->name} eliminado"]);
        $admin->delete();
    }

    public function getData(Component $component)
    {
        if ($component->filter) {
            return Admin::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
        }
        return Admin::paginate(15);
    }

    public function conditionalDelete(Component $component, $modelId)
    {
        return NetworkOperator::whereAdminId($modelId)->exists();
    }
}
