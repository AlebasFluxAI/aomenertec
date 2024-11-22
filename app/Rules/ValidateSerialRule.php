<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use Closure;

class ValidateSerialRule implements Rule
{
    protected $errorMessage;
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
                    $this->errorMessage = "El serial proporcionado no existe.";
                    return false;
                }
            }

            // Validar si el equipo está asignado a un cliente.
            if ($equipment->clients()->first() == null) {
                $this->errorMessage = "El serial proporcionado no está asignado a ningún cliente.";
                return false;
            }
        } else {
            $this->errorMessage = "No se encontró un tipo de equipo válido.";
            return false;
        }

        return true;
    }

    /**
     * Mensaje de error.
     */
    public function message(): string
    {
        return $this->errorMessage ?? "El serial proporcionado no es válido.";
    }
}
