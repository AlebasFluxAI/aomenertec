<?php

namespace App\Http\Services\V1\Admin\User;

use App\Http\Livewire\V1\Admin\User\EditUser;
use App\Http\Resources\V1\Menu;
use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\Client;
use App\Models\V1\Consumer;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function session;

class ProfileUserService extends Singleton
{
    public function mount(Component $component)
    {
        $component->model = $this->getModelByUser();
        if (Auth::user()->hasRole(User::TYPE_SUPER_ADMIN)) ;
        {
            $component->admins = Admin::get();
        }
    }

    private function getModelByUser()
    {
        return Menu::getUserModel();
    }

    public function getViewName()
    {
        return Menu::getHome();
    }

    public function conditionalDeleteAdmin(Component $component, $modelId)
    {
        return NetworkOperator::whereAdminId($modelId)->exists();
    }

    public function conditionalDeleteNetworkOperator(Component $component, $modelId)
    {
        return Client::whereNetworkOperatorId($modelId)->exists();
    }

    public function deleteAdmin(Component $component, $modelId)
    {
        $admin = Admin::find($modelId);
        $admin->user->enabled = false;
        foreach ($admin->adminClientTypes()->get() as $type){
            $type->delete();
        }
        foreach ($admin->adminClientTypes()->get() as $type){
            $type->delete();
        }
        foreach ($admin->adminEquipmentTypes()->get() as $type){
            $type->delete();
        }
        foreach ($admin->equipments()->get() as $type){
            $type->admin_id = "";
            $type->save();
        }
        if ($admin->configAdmin()->exists()){
            $admin->configAdmin()->delete();
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$admin->name} eliminado"]);
        $admin->delete();
    }

    public function deleteNetworkOperator(Component $component, $networkOperatorId)
    {
        $operator = NetworkOperator::find($networkOperatorId);
        $operator->user->enabled = false;
        foreach ($operator->equipments()->get() as $type){
            $type->network_operator_id = "";
            $type->save();
        }
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$operator->name} eliminado"]);
        $operator->delete();
    }

    public function conditionalLinkEquipmentNetworkOperator(Component $component, $modelId)
    {
        return !NetworkOperator::find($modelId)->admin->equipments()->exists();
    }



    public function conditionalMonitoring($clientId)
    {
        return !MicrocontrollerData::whereClientId($clientId)->exists();
    }

    public function blinkSupportPqrAvailability($supportId)
    {

        return Support::find($supportId)->blinkPqrAvailability();
    }
    public function disableAdmin(Component $component, $modelId)
    {
        $admin = Admin::find($modelId);
        $admin->enabled = !$admin->enabled;
        $admin->user->enabled = !$admin->user->enabled;
        $admin->push();
        if (!$admin->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else{
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);

        }
    }

    public function getEnabledAdmin(Component $component, $modelId)
    {
        return !Admin::find($modelId)->enabled;
    }

    public function getEnabledAuxAdmin(Component $component, $modelId)
    {
        if (!Admin::find($modelId)->enabled){
            return false;
        }
        return true;
    }

    public function disableNetworkOperator(Component $component, $modelId)
    {
        $operator = NetworkOperator::find($modelId);
        $operator->enabled = !$operator->enabled;
        $operator->user->enabled = !$operator->user->enabled;
        $operator->push();
        if (!$operator->enabled) {
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario desactivado"]);
        } else{
            $component->emitTo('livewire-toast', 'show', ['type' => 'warning', 'message' => "Usuario activado"]);

        }
    }

    public function getEnabledNetworkOperator(Component $component, $modelId)
    {
        return !NetworkOperator::find($modelId)->enabled;
    }

    public function getEnabledAuxNetworkOperator(Component $component, $modelId)
    {
        if (!NetworkOperator::find($modelId)->enabled){
            return false;
        }
        return true;
    }

}
