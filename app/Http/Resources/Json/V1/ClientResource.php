<?php

namespace App\Http\Resources\Json\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "equipments" => $this->clientEquipment($this),
        ];
    }

    private function clientEquipment($client)
    {
        $equipment_serial = [];
        foreach ($client->equipments as $equipment) {
            array_push($equipment_serial, ['type' => $equipment->equipmentType->type, 'serial' => $equipment->serial]);
        }
        return $equipment_serial;
    }
}
