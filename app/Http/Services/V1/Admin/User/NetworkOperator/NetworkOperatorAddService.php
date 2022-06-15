<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\EquipmentType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\SuperAdmin;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class NetworkOperatorAddService extends Singleton
{
    public function mount(Component $component)
    {
        $component->form_title = "Datos del operador de red";
        $component->fill($this->getMountData());
    }

    public function getMountData()
    {
        $user = Auth::user();
        if ($user->admin) {
            return [
                'admins' => [],
                "admin_id" => $user->admin->id,
                'picked' => false
            ];
        }
        return [
            'admin_id' => null,
            'admins' => [],
            'picked' => false
        ];
    }

    public function updatedAdminId(Component $component)
    {
        $component->picked = false;
        $component->admins = Admin::where('id', 'ilike', "%" . $component->admin_id . "%")
            ->orWhere('name', 'ilike', "%" . $component->admin_id . "%")->limit(3)->get();
    }

    public function setAdminId(Component $component, $admin)
    {
        $component->picked = true;
        $admin = json_decode($admin);
        $component->admin_id = $admin->id;
    }


    public function submitForm(Component $component)
    {
        DB::transaction(function () use ($component) {
            $component->validate();
            $operator = NetworkOperator::create($this->mapper($component));
            $user = User::create(array_merge($this->mapper($component), [
                "password" => bcrypt($component->password),
                "type" => User::TYPE_NETWORK_OPERATOR
            ]));
            $operator->update([
                "user_id" => $user->id
            ]);

            $component->redirectRoute("administrar.v1.usuarios.operadores.detalles", ["networkOperator" => $operator->id]);
        });
    }

    private function mapper($component)
    {
        return [
            "name" => $component->name,
            "last_name" => $component->last_name,
            "email" => $component->email,
            "phone" => $component->phone,
            "admin_id" => $component->admin_id,
            "identification" => $component->identification,
            "latitude" => $component->latitude,
            "longitude" => $component->longitude,
        ];
    }
}
