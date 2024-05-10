<?php

namespace App\Http\Controllers\V1;

use App\Models\V1\Client;
use App\Models\V1\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->guard = "api";
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth("api")->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 1
        ]);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth("api")->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth("api")->refresh());
    }

    public function joblist()
    {
        $user = auth("api")->user();
        $clients = $user->technician->clients;

        $pass = "jghsdjfg626FFDS5266s";
        $pass1 = "jkdhjk54858DDS55";
        $response = [];

        foreach ($clients as $client) {
            $gabinete = $client->equipments()->whereEquipmentTypeId(7)->first();
            $orders = $client->workOrders()->whereStatus(WorkOrder::WORK_ORDER_STATUS_OPEN)->get();
            if(count($orders)>0) {
                array_push($response, [
                    'uid' => $client->networkOperator->identification,
                    'did' => $gabinete ? $gabinete->serial : null,
                    'ssid' => $gabinete ? 'wifi_' . $gabinete->serial : 'wifi_xxx',
                    'password' => $client->identification,
                    'nombre' => ($client->alias ?? $client->name),
                    'codigo_cliente' => $client->code,
                    'ubicacion' => json_decode($client->addresses()->first()->here_maps),
                    'celular' => $client->phone,
                    "pass" => $pass,
                    "equipments" => $this->clientEquipment($client),
                    "orders" => $orders,
                ]);
            }
        }
        return response()->json($response);
    }

    public function ordersUpdate(Request $request): JsonResource
    {
        dd($request->get());
       //return $this->configurationClientService->setAlertLimitsForSerial($request);
    }

    private function clientEquipment(Client $client)
    {
        $equipment_serial = [];
        foreach ($client->equipments as $equipment) {
            array_push($equipment_serial, ['type' => $equipment->equipmentType->type, 'serial' => $equipment->serial]);
        }
        return $equipment_serial;
    }
}
