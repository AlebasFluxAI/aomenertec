<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use Closure;

class ValidateSerialRule implements Rule
{
    /**
     * Determina si la validación pasa.
     */
    public function passes($attribute, $value): bool
    {
        $equipmentType = EquipmentType::where('type', 'MEDIDOR ELECTRICO')->first();

        if ($equipmentType) {
            $equipment = Equipment::where('equipment_type_id', $equipmentType->id)
                ->where('serial', $value)->first();

            if (!$equipment) {
                $equipmentType = EquipmentType::where('type', 'GABINETE')->first();
                $equipment = Equipment::where('equipment_type_id', $equipmentType->id)
                    ->where('serial', $value)->first();

                if (!$equipment) {
                    return false;
                }
            } elseif (!$equipment->clients()->exists()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Mensaje de error.
     */
    public function message(): string
    {
        return "El serial proporcionado no es válido o no está asignado a ningún cliente.";
    }
}
