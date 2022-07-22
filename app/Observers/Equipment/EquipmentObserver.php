<?php

namespace App\Observers\Equipment;

use App\Models\V1\Equipment;
use Illuminate\Support\Facades\Auth;

class EquipmentObserver
{
    public function creating(Equipment $equipment)
    {
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
