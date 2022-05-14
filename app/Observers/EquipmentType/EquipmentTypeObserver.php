<?php

namespace App\Observers\EquipmentType;

use App\Models\V1\EquipmentType;

class EquipmentTypeObserver
{
    /**
     * Handle the EquipmentType "created" event.
     *
     * @param EquipmentType $equipmentType
     * @return void
     */
    public function deleting(EquipmentType $equipmentType)
    {

        if ($equipmentType->equipment->count() > 0) {
            abort(422, "No es posible borrar este tipo de equipo ya esta asignado a uno o mas equipos");
        }
    }

}
