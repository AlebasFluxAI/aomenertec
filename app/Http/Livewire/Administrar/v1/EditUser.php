<?php

namespace App\Http\Livewire\Administrar\v1;

use App\Models\v1\Seller;
use App\Models\v1\Team;
use App\Models\v1\User;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EditUser extends Component
{
    public $messageP;
    public $identification;
    public $name;
    public $phone;
    public $email;
    public $role;
    public $roles = [];
    public $network_operators = [];
    public $network_operator_id;
    public $picked;
    public $network_operator;
    public $user;
    public $user_id;
    public $pickedU;
    public $messageU;
    public $users = [];

    protected $rules = [
        'network_operator' => 'required|min:2',
        'identification' => 'required|min:6',
        'name' => 'required|min:8',
        'phone' => 'min:7',
        'email' => 'required|email',
        'user' => 'required|min:2',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount()
    {
        $this->identification = "";
        $this->name = "";
        $this->phone = "";
        $this->email = "";
        $this->role = "";
        $this->roles = Role::all();
        $this->network_operator = "";
        $this->picked = false;
        $this->pickedU = false;
        $this->network_operator_id = "";
        $this->network_operators = [];
        $this->messageP = "Ingrese identificación del operador de red";
        $this->messageU = "Ingrese identificación del usuario";
        $this->user = "";
        $this->user_id = "";
        $this->users = [];
    }
    public function updatedUser()
    {
        $this->pickedU = false;
        $this->messageU = "No hay usuarios registrados con esta identificación";

        if ($this->user != "") {
            $this->users = User::where("identification", "like", '%' . $this->user . "%")
                ->take(3)
                ->get();
        }
    }
    public function assignUser($user)
    {
        $obj = json_decode($user);
        $user = User::find($obj->id);
        $this->user = $user->identification;
        $this->user_id = $user->id;
        $this->identification = $user->identification;
        $this->name = $user->name;
        $this->phone = $user->phone;
        $this->email = $user->email;
        $this->pickedU = true;
        $this->picked = true;
        $this->messageU = "";
        $this->messageP = "";
        if ($user->hasRole('seller')) {
            $this->role = "seller";
            $this->network_operator_id = $user->seller->network_operator_id;
            $this->network_operator = $user->seller->networkOperator->user->identification;
        }elseif ($user->hasRole('technician')) {
            $this->role = "technician";
            $this->network_operator_id = $user->technician->network_operator_id;
            $this->network_operator = $user->technician->networkOperator->user->identification;
        }elseif ($user->hasRole('consumer')) {
            $this->role = "consumer";
            $this->network_operator_id = $user->consumer->network_operator_id;
            $this->network_operator = $user->consumer->networkOperator->user->identification;
        }else {
                $this->role = $user->getRoleNames();
                $this->network_operator_id = "";
                $this->network_operator = "";
                $this->picked = false;
                $this->messageU = "";
                $this->messageP = "Digite identification del operador de red";
        }
    }
    public function assignUserFirst()
    {
        if (!empty($this->user)) {
            $user = User::where("identification", "like", '%' . $this->user . "%")
                ->first();
            if ($user) {
                $this->user = $user->identification;
                $this->user_id = $user->id;
                $this->identification = $user->identification;
                $this->name = $user->name;
                $this->phone = $user->phone;
                $this->email = $user->email;
                $this->role = $user->getRoleNames();
                $this->pickedU = true;
                $this->picked = true;
                $this->messageU = "";
                $this->messageP = "";
                if ($user->hasRole('seller')) {
                    $this->network_operator_id = $user->seller->network_operator_id;
                    $this->network_operator = $user->seller->networkOperator->user->identification;
                }elseif ($user->hasRole('technician')) {
                    $this->network_operator_id = $user->technician->network_operator_id;
                    $this->network_operator = $user->technician->networkOperator->user->identification;
                }elseif ($user->hasRole('consumer')) {
                    $this->network_operator_id = $user->consumer->networkOperator_id;
                    $this->network_operator = $user->consumer->networkOperator->user->identification;
                }else {
                    $this->network_operator_id = "";
                    $this->network_operator = "";
                    $this->picked = false;
                    $this->messageU = "";
                    $this->messageP = "Digite identification del operador de red";
                }
            } else {
                $this->user = "...";
            }
        }
    }

    public function updatedNetworkOperator()
    {
        $this->picked = false;
        $this->messageP = "No hay operador de red registrados con esta identificación";

        if ($this->network_operator != "") {
            $this->network_operators = User::role('network_operator')->where("identification", "like", '%' . $this->network_operator . "%")
                ->where("identification", "!=", $this->user)
                ->take(3)->get();
        }
    }
    public function assignNetworkOperator($network_operator)
    {
        $obj = json_decode($network_operator);
        $this->network_operator = $obj->identification;
        $this->network_operator_id = $obj->id;
        $this->picked = true;
    }
    public function assignNetworkOperatorFirst()
    {
        if (!empty($this->network_operator)) {
            $user = App\User::role('network_operator')->where("identification", "like", '%' . $this->network_operator . "%")
                ->where("identification", "!=", $this->user)
                ->first();
            if ($user) {
                $this->network_operator = $user->identification;
                $this->network_operator_id= $user->id;
            } else {
                $this->network_operator = "...";
            }
            $this->picked = true;
        }
    }

    public function edit()
    {
        if (auth()->user()->can('edit_user')) {
            $user = User::find($this->user_id);
            $user->name = $this->name;
            $user->email = $this->email;
            $user->identification = $this->identification;
            $user->phone = $this->phone;
            $role=$user->getRoleNames();
            $user->save();
            $user->syncRoles([$this->role]);
            $role_update = $user->getRoleNames();
            if ($role != $role_update){
                if ($role == 'seller') {
                    $user->seller->delete();
                }elseif ($role == 'technician') {
                    $user->technician->delete();
                }elseif ($role == 'consumer') {
                    $user->consumer->delete();
                }elseif ($role == 'support') {
                    $user->support->delete();
                }
            }
            if ($user->hasRole('seller')) {
                Seller::updateOrCreate(
                    ['user_id' => $user->id],
                    ['network_operator_id' => $this->network_operator_id]
                );
            }elseif ($user->hasRole('technician')) {
                Technician::updateOrCreate(
                    ['user_id' => $user->id],
                    ['network_operator_id' => $this->network_operator_id]
                );
            }elseif ($user->hasRole('consumer')) {
                Consumer::updateOrCreate(
                    ['user_id' => $user->id],
                    ['network_operator_id' => $this->network_operator_id]
                );
            }elseif ($user->hasRole('suport')) {
                Support::updateOrCreate(
                    ['user_id' => $user->id],
                );
            }
            session()->flash('message', 'Usuario '.$this->name.' actualizado con exito. ');
            $this->resetExcept('roles');
        }
    }
    public function eliminar()
    {
        if (auth()->user()->can('delete_user')) {
            $user = User::find($this->user_id);
            if ($user->hasRole('seller')) {
                $user->seller->delete();
            }elseif ($user->hasRole('technician')) {
                $user->technician->delete();
            }elseif ($user->hasRole('consumer')) {
                $user->consumer->delete();
            }elseif ($user->hasRole('suport')) {
                $user->support->delete();
            }
            $user->delete();
            session()->flash('eliminate', 'Usuario '.$this->name.' Eliminado con exito. ');
            $this->resetExcept('roles');
        }
    }
    public function render()
    {
        return view('livewire.administrar.v1.edit-user')
            ->extends('layouts.v1.app');
    }
}
