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
        if(Auth::user()->hasRole(User::TYPE_SUPER_ADMIN));
        {
            $component->admins = Admin::all();
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

    public function deleteNetworkOperator(Component $component, $networkOperatorId)
    {
        $operatorName = NetworkOperator::find($networkOperatorId)->name;
        NetworkOperator::whereId($networkOperatorId)->delete();
        $component->emitTo('livewire-toast', 'show', ['type' => 'success', 'message' => "{$operatorName} eliminado"]);
    }

    public function conditionalNetworkOperatorDelete($networkOperatorId)
    {
        return Client::whereNetworkOperatorId($networkOperatorId)->exists();
    }

    public function conditionalMonitoring($clientId)
    {
        return !MicrocontrollerData::whereClientId($clientId)->exists();
    }
}
