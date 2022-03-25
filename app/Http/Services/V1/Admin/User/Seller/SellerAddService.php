<?php

namespace App\Http\Services\V1\Admin\User\Seller;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SellerAddService extends Singleton
{
    public function mount(Component $component)
    {

        $component->fill($this->getMountData());
    }

    private function getMountData()
    {
        $user = Auth::user();
        if ($user->hasRole(User::TYPE_NETWORK_OPERATOR)) {
            return [
                'admins' => [],
                "network_operator_id" => $user->networkOperator->id,
                'picked' => false
            ];
        }

        return [
            'network_operator_id' => null,
            'admins' => [],
            'picked' => false
        ];
    }

    public function updatedNetworkOperatorId(Component $component)
    {

        $component->picked = false;
        $component->networkOperators = NetworkOperator::where('id', 'ilike', "%" . $component->network_operator_id . "%")
            ->orWhere('name', 'ilike', "%" . $component->network_operator_id . "%")->limit(3)->get();

    }

    public function setNetworkOperatorId(Component $component, $admin)
    {
        $component->picked = true;
        $admin = json_decode($admin);
        $component->network_operator_id = $admin->id;
    }


    public function submitForm(Component $component)
    {

        $seller = Seller::create($this->mapper($component));
        $user = User::create(array_merge($this->mapper($component), [
            "password" => bcrypt($component->password),
            "type" => User::TYPE_SELLER
        ]));
        $seller->update([
            "user_id" => $user->id
        ]);

        $component->redirectRoute("administrar.v1.usuarios.vendedores.detalles", ["seller" => $seller->id]);
    }

    private function mapper($component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "network_operator_id" => $component->network_operator_id,
            "identification" => $component->identification
        ];
    }
}
