<?php

namespace App\Http\Controllers\V1\MqttInput;

use App\Http\Controllers\V1\Controller;
use App\Jobs\V1\Enertec\PushRealTimeMicrocontrollerDataJob;
use Illuminate\Http\Request;

class MqttRealTimeInputController extends Controller
{
    public function __invoke(Request $request)
    {
        if (!$request->has('message') || empty($request->message)) {
            return response()->json(['error' => 'El campo message es requerido'], 422);
        }

        dispatch(new PushRealTimeMicrocontrollerDataJob($request->message));
    }
}
