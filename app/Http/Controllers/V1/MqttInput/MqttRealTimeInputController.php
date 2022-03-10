<?php

namespace App\Http\Controllers\V1\MqttInput;

use App\Http\Controllers\V1\Controller;
use App\Jobs\V1\Enertec\SaveMicrocontrollerDataJob;
use App\Models\V1\EquipmentType;
use Illuminate\Http\Request;

class MqttRealTimeInputController extends Controller
{
    public function __invoke(Request $request)
    {
        dispatch(new SaveMicrocontrollerDataJob($request->message));

    }

}
