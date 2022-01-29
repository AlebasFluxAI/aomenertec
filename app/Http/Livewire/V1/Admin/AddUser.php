<?php

namespace App\Http\Livewire\V1\Admin;

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
use function view;

class AddUser extends Component
{
    public $password;
    public $identification;
    public $name;
    public $phone;
    public $email;
    public $role;
    public $roles;
    public $network_operators = [];
    public $network_operator_id;
    public $picked;
    public $message;
    public $network_operator;

    protected $rules = [
        'network_operator' => 'required|min:2',
        'identification' => 'required|min:6|unique:users,identification',
        'name' => 'required|min:8',
        'phone' => 'min:7',
        'email' => 'required|email|unique:users,email',
        'role' => 'required',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function mount()
    {
        $this->password = "";
        $this->identification = "";
        $this->name = "";
        $this->phone = "";
        $this->email = "";
        $this->role = "";
        $this->roles = Role::all();
        $this->network_operator = "";
        $this->picked = false;
        $this->network_operator_id = "";
        $this->network_operators = [];
        $this->message = "Ingrese identificación del operador de red";
    }

    public function updatedNetworkOperator()
    {
        $this->picked = false;
        $this->message = "No hay operador de red registrado con esta identificación";

        if ($this->network_operator != "") {
            $this->network_operators = User::role('network_operator')->where("identification", "like", '%' . $this->network_operator . "%")
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
    public function assingnNetworkOperatorFirst()
    {
        if (!empty($this->network_operator)) {
            $usuario = User::role('network_operator')->where("identification", "like", '%' . $this->network_operator . "%")
                ->first();
            if ($usuario) {
                $this->network_operator = $usuario->identification;
                $this->network_operator_id= $usuario->id;
            } else {
                $this->network_operator = "...";
            }
            $this->picked = true;
        }
    }

    public function save()
    {
        if (auth()->user()->can('add_user')) {
            $this->password = Str::random(8);
            $user = User::firstOrCreate([
                'name' => $this->name,
                'email' => $this->email,
                'password' => bcrypt($this->password),
                'remember_token' => Str::random(60),
                'identification' => $this->identification,
                'phone' => $this->phone,
            ]);
            $user->assignRole($this->role);
            if ($user->hasRole('network_operator')) {
                NetworkOperator::create([
                    'user_id' => $user->id,
                ]);
            } elseif ($user->hasRole('seller')) {
                Seller::create([
                    'user_id' => $user->id,
                    'network_operator_id' => $this->network_operator_id,
                ]);
            } elseif ($user->hasRole('technician')) {
                Technician::create([
                    'user_id' => $user->id,
                    'network_operator_id' => $this->network_operator_id,
                ]);
            } elseif ($user->hasRole('consumer')) {
                Consumer::create([
                    'user_id' => $user->id,
                    'network_operator_id' => $this->network_operator_id,
                ]);
            } elseif ($user->hasRole('support')) {
                Support::create([
                    'user_id' => $user->id,
                ]);
            }
            /*

             Enviar email al usuario creado con la contraseña temporal

            */
            session()->flash('message', 'Usuario '.$this->name.' creado con exito. Contraseña temporal: '.$this->password);
        }
        $this->resetExcept('roles');
    }
    public function render()
    {
        return view('livewire.administrar.v1.add-user')
            ->extends('layouts.v1.app');
    }
}
