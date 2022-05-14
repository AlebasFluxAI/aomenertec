<?php

namespace App\Observers\Equipment;

use App\Models\V1\Equipment;
use Illuminate\Support\Facades\Auth;

class EquipmentObserver
{
    public function creating(Equipment $equipment)
    {
        if ($admin = Auth::user()->admin) {
            $equipment->admin_id = $admin->id;
        }
    }

    public function deleting(Equipment $equipment)
    {
        if ($equipment->assigned) {
            abort(422, "Este equipo no puede ser borrado");
        }
    }
}
