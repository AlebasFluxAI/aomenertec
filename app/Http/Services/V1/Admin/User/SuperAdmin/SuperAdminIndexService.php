<?php

namespace App\Http\Services\V1\Admin\User\SuperAdmin;

use App\Http\Services\Singleton;
use App\Models\V1\SuperAdmin;
use Livewire\Component;

class SuperAdminIndexService extends Singleton
{
    public function mount(Component $component, $model)
    {
        $component->fill([
            'model' => $model,
        ]);
    }


    public function edit(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.superadmin.editar", ["superAdmin" => $modelId]);
    }

    public function details(Component $component, $modelId)
    {
        $component->redirectRoute("administrar.v1.usuarios.superadmin.detalles", ["superAdmin" => $modelId]);
    }

    public function delete(Component $component, $modelId)
    {
        $super_admin = SuperAdmin::find($modelId);
        $super_admin->user->enabled = false;
        $super_admin->push();
        $super_admin->delete();
        $component->emitTo('livewire-toast', 'show', ['type' => 'info', 'message' => "Usuario eliminado"]);

    }

    public function disableSuperAdmin(Component $component, $modelId)
    {
        $super_admin = SuperAdmin::find($modelId);
        $super_admin->enabled = !$super_admin->enabled;
        $super_admin->user->enabled = !$super_admin->user->enabled;
        $super_admin->push();
        if (!$super_admin->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else{
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);

        }
    }

    public function getEnabledSuperAdmin(Component $component, $modelId)
    {
        return !SuperAdmin::find($modelId)->enabled;
    }

    public function getEnabledAuxSuperAdmin(Component $component, $modelId)
    {
        if (!SuperAdmin::find($modelId)->enabled){
            return false;
        }
        return true;
    }

    public function getData(Component $component)
    {
        if ($component->filter) {
            return SuperAdmin::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->paginate(15);
        }
        return SuperAdmin::paginate(15);
    }
}
