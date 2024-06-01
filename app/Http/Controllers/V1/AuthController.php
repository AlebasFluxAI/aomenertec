<?php

namespace App\Http\Controllers\V1;

use App\Models\V1\Client;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
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

    public function ordersUpdate(Request $request)
    {
        $order = json_decode($request->get('order'), true);
        $orderModel = WorkOrder::find($order['id']);
        if ($orderModel) {

            if($orderModel->status == WorkOrder::WORK_ORDER_STATUS_CLOSED){
                return response()->json([
                    'success' => false,
                    'message' => 'The order is already closed.'
                ], 409); // HTTP 409 Conflict
            }
            if ($orderModel->type == WorkOrder::WORK_ORDER_TYPE_READING) {
                if (array_key_exists('raw_json', $order)) {
                    $rawJson = $order['raw_json'];
                    $source_timestamp = Carbon::create();
                    $source_timestamp->setTimestamp($rawJson['timestamp']);
                    $microcontroller_data = MicrocontrollerData::create([
                        'raw_json' => json_encode($rawJson),
                        "source_timestamp" => $source_timestamp->format('Y-m-d H:i:s'),
                        'manually' => true,
                        'status' => MicrocontrollerData::SUCCESS_TIMESTAMP
                    ]);
                    $order['microcontroller_data_id'] = $microcontroller_data->id;
                    unset($order['raw_json']);
                } else{
                    return response()->json([
                        'success' => false,
                        'message' => 'raw_json not found'
                    ], 409); // HTTP 409 Conflict
                }
            }
            if (array_key_exists('images', $order)) {
                $images = $order['images'];
                foreach ($images as $image) {
                    $image_file = $request->file($image['name']);
                    $orderModel->saveImageOnModelWithMorphMany($image_file, "evidences", $image['description']);
                    if($microcontroller_data){
                        $microcontroller_data->saveImageOnModelWithMorphMany($image_file, "evidences", $image['description']);
                    }
                }
                unset($order['images']);
            }

            unset($order['id']);
            $orderModel->update($order);

            // Devolver una respuesta JSON de éxito
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'order' => $order
            ], 200);
        } else {
            // Devolver una respuesta JSON de error si no se encuentra la orden
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
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
