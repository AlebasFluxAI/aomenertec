<?php

namespace App\Http\Controllers\V1\MqttInput;

use App\Http\Controllers\V1\Controller;
use App\Jobs\V1\Enertec\PushRealTimeMicrocontrollerDataJob;
use Illuminate\Http\Request;

class MqttRealTimeInputController extends Controller
{
    public function __invoke(Request $request)
    {
        dispatch(new PushRealTimeMicrocontrollerDataJob($request->message));
    }
}
