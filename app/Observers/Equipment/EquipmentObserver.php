<?php

namespace App\Observers\Equipment;

use App\Models\V1\Equipment;
use Illuminate\Support\Facades\Auth;

class EquipmentObserver
{
    public function updating(Equipment $equipment)
    {
        if ($equipment->isDirty("admin_id") and $equipment->admin_id) {
            $equipment->has_admin = true;
        }
        if ($equipment->isDirty("network_operator_id") and $equipment->network_operator_id) {
            $equipment->has_network_operator = true;
        }
        if ($equipment->isDirty("technician_id") and $equipment->technician_id) {
            $equipment->has_technician = true;
        }
        if ($equipment->isDirty("client_id") and $equipment->client_id) {
            $equipment->has_client = true;
        }
    }

    public function creating(Equipment $equipment)
    {
        if (!$equipment->description) {
            $equipment->description = "Sin descripcion";
        }
        if (Auth::check()) {
            if ($admin = Auth::user()->admin) {
                $equipment->admin_id = $admin->id;
            }
            if ($networkOperator = Auth::user()->networkOperator) {
                $equipment->network_operator_id = $networkOperator->id;
                $equipment->admin_id = $networkOperator->admin_id;
            }
        }
    }
}
