<?php

namespace App\Http\Controllers\V1\MqttInput;

use App\Http\Controllers\V1\Controller;
use App\Models\V1\EquipmentType;
use Illuminate\Http\Request;

class MqttInputController extends Controller
{
    public function __invoke(Request $request)
    {
        EquipmentType::create([
            'type' => "Nuevo",
            "description" => "Nuevo"
        ]);
    }
}
