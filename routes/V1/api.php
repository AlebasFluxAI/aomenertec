<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ConfigurationClient\ConfigurationClientController;
use App\Http\Controllers\V1\MqttInput\MqttInputController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("/v1/mqtt_input", MqttInputController::class);
Route::post("/v1/mqtt_input/real-time", \App\Http\Controllers\V1\MqttInput\MqttRealTimeInputController::class);


Route::prefix('v1/config')->middleware(['event_queue_validation', 'token_api_validation'])->group(function () {
    Route::get("/set-status-coil", [ConfigurationClientController::class, 'setStatusCoilForSerial']);
    Route::get("/get-status-coil", [ConfigurationClientController::class, 'getStatusCoilForSerial']);
    Route::get("/get-date", [ConfigurationClientController::class, 'getDateForSerial']);
    Route::get("/set-date", [ConfigurationClientController::class, 'setDateForSerial']);
    Route::get("/get-config-sensor", [ConfigurationClientController::class, 'getTypeSensorForSerial']);
    Route::get("/set-config-sensor", [ConfigurationClientController::class, 'setTypeSensorForSerial']);
    Route::get("/get-status-sensor", [ConfigurationClientController::class, 'getStatusSensorForSerial']);
});


Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::post('job-list', 'joblist');
        Route::post('me', 'me');
    });
});
