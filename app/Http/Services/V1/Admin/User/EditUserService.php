<?php

namespace App\Http\Services\V1\Admin\User;

use App\Http\Services\Singleton;
use App\Models\V1\Consumer;
use App\Models\V1\Seller;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function session;

class EditUserService extends Singleton
{
    public function mount(Component $component)
    {
        $component->identification = "";
        $component->name = "";
        $component->phone = "";
        $component->email = "";
        $component->role = "";
        $component->roles = Role::all();
        $component->network_operator = "";
        $component->picked = false;
        $component->pickedU = false;
        $component->network_operator_id = "";
        $component->network_operators = [];
        $component->messageP = "Ingrese identificación del operador de red";
        $component->messageU = "Ingrese identificación del usuario";
        $component->user = "";
        $component->user_id = "";
        $component->users = [];
    }

    public function updatedNetworkOperator(Component $component)
    {
        $component->picked = false;
        $component->messageP = "No hay operador de red registrados con esta identificación";

        if ($component->network_operator != "") {
            $component->network_operators = User::role('network_operator')->where("identification", "like", '%' . $component->network_operator . "%")
                ->where("identification", "!=", $component->user)
                ->take(3)->get();
        }
    }

    public function updatedUser(Component $component)
    {
        $component->pickedU = false;
        $component->messageU = "No hay usuarios registrados con esta identificación";

        if ($component->user != "") {
            $component->users = User::where("identification", "like", '%' . $component->user . "%")
                ->take(3)
                ->get();
        }
    }

    public function assignUser(Component $component, $user)
    {
        $obj = json_decode($user);
        $user = User::find($obj->id);
        $component->user = $user->identification;
        $component->user_id = $user->id;
        $component->identification = $user->identification;
        $component->name = $user->getRoleNames();
        $component->phone = $user->phone;
        $component->email = $user->email;
        $component->role = $user->getRoleNames();
        $component->pickedU = true;
        $component->picked = true;
        $component->messageU = "";
        $component->messageP = "";
        if ($user->hasRole('seller')) {
            $component->network_operator_id = $user->seller->network_operator_id;
            $component->network_operator = $user->seller->networkOperator->user->identification;
        } elseif ($user->hasRole('technician')) {
            $component->network_operator_id = $user->technician->network_operator_id;
            $component->network_operator = $user->technician->networkOperator->user->identification;
        } elseif ($user->hasRole('consumer')) {
            $component->role = "consumer";
            $component->network_operator_id = $user->consumer->network_operator_id;
            $component->network_operator = $user->consumer->networkOperator->user->identification;
        } else {
            $component->network_operator_id = "";
            $component->network_operator = "";
            $component->picked = false;
            $component->messageU = "";
            $component->messageP = "Digite identification del operador de red";
        }
    }

    public function assignUserFirst(Component $component)
    {
        if (!empty($component->user)) {
            $user = User::where("identification", "like", '%' . $component->user . "%")
                ->first();
            if ($user) {
                $component->user = $user->identification;
                $component->user_id = $user->id;
                $component->identification = $user->identification;
                $component->name = $user->name;
                $component->phone = $user->phone;
                $component->email = $user->email;
                $component->pickedU = true;
                $component->picked = true;
                $component->messageU = "";
                $component->messageP = "";
                if ($user->hasRole('seller')) {
                    $component->role = "seller";
                    $component->network_operator_id = $user->seller->network_operator_id;
                    $component->network_operator = $user->seller->networkOperator->user->identification;
                } elseif ($user->hasRole('technician')) {
                    $component->role = "technician";
                    $component->network_operator_id = $user->technician->network_operator_id;
                    $component->network_operator = $user->technician->networkOperator->user->identification;
                } elseif ($user->hasRole('consumer')) {
                    $component->role = "consumer";
                    $component->network_operator_id = $user->consumer->networkOperator_id;
                    $component->network_operator = $user->consumer->networkOperator->user->identification;
                } else {
                    $component->role = $user->getRoleNames();
                    $component->network_operator_id = "";
                    $component->network_operator = "";
                    $component->picked = false;
                    $component->messageU = "";
                    $component->messageP = "Digite identification del operador de red";
                }
            } else {
                $component->user = "...";
            }
        }
    }

    public function assignNetworkOperator(Component $component, $network_operator)
    {
        $obj = json_decode($network_operator);
        $component->network_operator = $obj->identification;
        $component->network_operator_id = $obj->id;
        $component->picked = true;
    }

    public function assignNetworkOperatorFirst(Component $component)
    {
        if (!empty($component->network_operator)) {
            $user = User::role('network_operator')
                ->where("identification", "like", '%' . $component->network_operator . "%")
                ->where("identification", "!=", $component->user)
                ->first();
            if ($user) {
                $component->network_operator = $user->identification;
                $component->network_operator_id = $user->id;
            } else {
                $component->network_operator = "...";
            }
            $component->picked = true;
        }
    }

    public function edit(Component $component)
    {
        if (auth()->user()->can('edit_user')) {
            $user = User::find($component->user_id);
            $user->name = $component->name;
            $user->email = $component->email;
            $user->identification = $component->identification;
            $user->phone = $component->phone;
            $role = $user->getRoleNames();
            $user->save();
            $user->syncRoles([$component->role]);
            $role_update = $user->getRoleNames();
            if ($role[0] != $role_update) {
                if ($role == 'seller') {
                    $user->seller->delete();
                } elseif ($role == 'technician') {
                    $user->technician->delete();
                } elseif ($role == 'consumer') {
                    $user->consumer->delete();
                } elseif ($role == 'support') {
                    $user->support->delete();
                }
            }
            if ($user->hasRole('seller')) {
                Seller::updateOrCreate(
                    ['user_id' => $user->id],
                    ['network_operator_id' => $component->network_operator_id]
                );
            } elseif ($user->hasRole('technician')) {
                Technician::updateOrCreate(
                    ['user_id' => $user->id],
                    ['network_operator_id' => $component->network_operator_id]
                );
            } elseif ($user->hasRole('consumer')) {
                Consumer::updateOrCreate(
                    ['user_id' => $user->id],
                    ['network_operator_id' => $component->network_operator_id]
                );
            } elseif ($user->hasRole('suport')) {
                Support::updateOrCreate(
                    ['user_id' => $user->id],
                );
            }
            session()->flash('message', 'Usuario ' . $component->name . ' actualizado con exito. ');
            $component->resetExcept('roles');
        }
    }

    public function delete(Component $component)
    {
        if (auth()->user()->can('delete_user')) {
            $user = User::find($component->user_id);
            if ($user->hasRole('seller')) {
                $user->seller->delete();
            } elseif ($user->hasRole('technician')) {
                $user->technician->delete();
            } elseif ($user->hasRole('consumer')) {
                $user->consumer->delete();
            } elseif ($user->hasRole('suport')) {
                $user->support->delete();
            }
            $user->delete();
            session()->flash('eliminate', 'Usuario ' . $component->name . ' Eliminado con exito. ');
            $component->resetExcept('roles');
        }
    }
}
