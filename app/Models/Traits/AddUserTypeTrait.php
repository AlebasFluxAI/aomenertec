<?php

namespace App\Models\Traits;

use App\Models\V1\NetworkOperator;
use App\Models\V1\Technician;
use Livewire\Component;

trait AddUserTypeTrait
{
    public $user_type_network_operator;
    public $user_type_technician;

    public function updatedUserTypeNetworkOperator()
    {
        if (!$this->user_type_network_operator) {
            $this->user_type_technician = false;
        }
    }

    public function updatedUserTypeTechnician()
    {

        if ($this->user_type_technician) {
            $this->user_type_network_operator = true;
        }
    }

    public function createNetworkOperator($user_id, Component $component, $admin_id)
    {
        return NetworkOperator::create([
            "name" => $component->model['name'],
            "last_name" => $component->model['last_name'],
            "email" => $component->model['email'],
            "phone" => $component->model['phone'],
            "indicative" => $component->model['indicative'],
            "identification" => $component->model['identification'],
            "user_id" => $user_id,
            "admin_id" => $admin_id,

        ]);
    }

    public function createTechnician($user_id, Component $component, $admin_id, $network_operator_id)
    {
        return Technician::create([
            "name" => $component->model['name'],
            "last_name" => $component->model['last_name'],
            "email" => $component->model['email'],
            "phone" => $component->model['phone'],
            "identification" => $component->model['identification'],
            "indicative" => $component->model['indicative'],
            "user_id" => $user_id,
            "admin_id" => $admin_id,
            "network_operator_id" => $network_operator_id,
        ]);
    }

}
