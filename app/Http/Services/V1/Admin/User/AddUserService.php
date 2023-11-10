<?php

namespace App\Http\Services\V1\Admin\User;

use App\Http\Services\Singleton;
use App\Models\V1\Consumer;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class AddUserService extends Singleton
{
    public function updated(Component $component, $propertyName)
    {
        $component->validateOnly($propertyName);
    }

    public function mount(Component $component)
    {
        $component->password = "";
        $component->identification = "";
        $component->name = "";
        $component->phone = "";
        $component->email = "";
        $component->role = "";
        $component->roles = Role::all();
        $component->network_operator = "";
        $component->picked = false;
        $component->network_operator_id = "";
        $component->network_operators = [];
        $component->message = "Ingrese identificación del operador de red";
    }

    public function updatedNetworkOperator(Component $component)
    {
        $component->picked = false;
        $component->message = "No hay operador de red registrado con esta identificación";

        if ($component->network_operator != "") {
            $component->network_operators = User::role('network_operator')->where("identification", "like", '%' . $component->network_operator . "%")
                ->take(3)->get();
        }
    }

    public function assignNetworkOperator(Component $component, $network_operator)
    {
        $obj = json_decode($network_operator);
        $component->network_operator = $obj->identification;
        $component->network_operator_id = $obj->id;
        $component->picked = true;
    }

    public function assingnNetworkOperatorFirst(Component $component)
    {
        if (!empty($component->network_operator)) {
            $usuario = User::role('network_operator')->where("identification", "like", '%' . $component->network_operator . "%")
                ->first();
            if ($usuario) {
                $component->network_operator = $usuario->identification;
                $component->network_operator_id = $usuario->id;
            } else {
                $component->network_operator = "...";
            }
            $component->picked = true;
        }
    }

    public function save(Component $component)
    {
        if (auth()->user()->can('add_user')) {
            $component->password = Str::random(8);
            $user = User::firstOrCreate([
                'name' => $component->name,
                'email' => $component->email,
                'password' => bcrypt($component->password),
                'remember_token' => Str::random(60),
                'identification' => $component->identification,
                'phone' => $component->phone,
            ]);
            $user->assignRole($component->role);
            if ($user->hasRole('network_operator')) {
                NetworkOperator::create([
                    'user_id' => $user->id,
                ]);
            } elseif ($user->hasRole('seller')) {
                Seller::create([
                    'user_id' => $user->id,
                    'network_operator_id' => $component->network_operator_id,
                ]);
            } elseif ($user->hasRole('technician')) {
                Technician::create([
                    'user_id' => $user->id,
                    'network_operator_id' => $component->network_operator_id,
                ]);
            } elseif ($user->hasRole('consumer')) {
                Consumer::create([
                    'user_id' => $user->id,
                    'network_operator_id' => $component->network_operator_id,
                ]);
            } elseif ($user->hasRole('support')) {
                Support::create([
                    'user_id' => $user->id,
                ]);
            }
            /*

             Enviar email al usuario creado con la contraseña temporal

            */
            session()->flash('message', 'Usuario ' . $component->name . ' creado con exito. Contraseña temporal: ' . $component->password);
        }
    }
}
