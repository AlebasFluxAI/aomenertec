<?php

namespace App\Http\Controllers\V1;

use App\Jobs\V1\Enertec\WorkOrder\CreateReadTypeWorkOrderJob;
use App\Models\Model\V1\Firmware;
use App\Models\V1\Client;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\WorkOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'firmwares', 'firmware']]);
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
            $equipment = $this->clientEquipment($client);
            if(count($orders)>0) {
                if($gabinete) {
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
                        "equipments" => $equipment,
                        "orders" => $orders,
                    ]);
                }
            }
        }
        return response()->json($response);
    }
    public function orderCreate(Request $request)
    {
        $client = Client::find($request->get('client_id'));
        if ($client == null){
            return response()->json([
                'success' => false,
                'message' => 'Client not found'
            ], 409); // HTTP 409 Conflict
        }
        $order_type = $request->get('order_type');
        $userModel = $client->networkOperator->user;
        $technician = $client->clientTechnician()->first();
        if ($technician == null) {
            $technician = $client->networkOperator->technicians()->first();
        }
        if ($technician) {
            $technicianModel = $technician->user;
            $order = $client->workOrders()->create([
                "status" => WorkOrder::WORK_ORDER_STATUS_OPEN,
                "open_at" => Carbon::now(),
                "open_by" => $technicianModel->id,
                "description" => "orden prueba",
                "type" => $order_type,
                "technician_id" => $technician->id,
                "created_by_type" => $userModel::class,
                "created_by_id" => $userModel->id
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order
            ], 200);
        }
    }

    public function ordersUpdate(Request $request)
    {
        $order = json_decode($request->get('order'), true);
        $orderModel = WorkOrder::find($order['id']);
        $microcontroller_data = null;
        if ($orderModel) {
            // Validación de las imágenes antes de cualquier otra acción
            if (array_key_exists('images', $order)) {
                $images = $order['images'];
                foreach ($images as $image) {
                    $image_file = $request->file($image['name']);

                    // Verificar si el archivo fue cargado correctamente
                    if (!$image_file) {
                        return response()->json([
                            'success' => false,
                            'message' => 'One or more images are missing in the request.'
                        ], 400); // HTTP 400 Bad Request
                    }

                    // Validación del tamaño de la imagen
                    if ($image_file->getSize() > 6 * 1024 * 1024) { // 6MB en bytes
                        return response()->json([
                            'success' => false,
                            'message' => 'The image ' . $image['name'] . ' exceeds the maximum size of 6MB.'
                        ], 413); // HTTP 413 Payload Too Large
                    }
                }
            }
            if($orderModel->status == WorkOrder::WORK_ORDER_STATUS_CLOSED){
                return response()->json([
                    'success' => false,
                    'message' => 'The order is already closed.'
                ], 409); // HTTP 409 Conflict
            }
            if ($orderModel->type == WorkOrder::WORK_ORDER_TYPE_READING) {
                if (array_key_exists('raw_json', $order)) {
                    $rawJson = $order['raw_json'];
                    if ($rawJson != null) {
                        $source_timestamp = Carbon::create();
                        $source_timestamp->setTimestamp($rawJson['timestamp']);
                        $microcontroller_data = MicrocontrollerData::create([
                            'raw_json' => json_encode($rawJson),
                            "source_timestamp" => null,
                            'manually' => true,
                            'status' => MicrocontrollerData::PENDING_TIMESTAMP
                        ]);
                        $order['microcontroller_data_id'] = $microcontroller_data->id;
                        unset($order['raw_json']);
                    }
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
    public function firmwares(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);
        if($request->password != '123456789'){
            return response()->json(['error' => 'Invalidate password'], 404);
        }
        // Obtener todos los registros de Firmware
        $firmwares = Firmware::all();

        // Devolver los registros en formato JSON
        return response()->json($firmwares);
    }

    public function firmware(Request $request, $id)
    {
        $request->validate([
            'password' => 'required'
        ]);
        if($request->password != '123456789'){
            return response()->json(['error' => 'Invalidate password'], 404);
        }
        // Encontrar el firmware por su ID
        $firmware = Firmware::find($id);

        // Verificar si el firmware existe
        if (!$firmware) {
            return response()->json(['error' => 'Firmware not found'], 404);
        }

        $evidence = $firmware->evidences()->first();
        if (!$evidence) {
            return response()->json(['error' => 'Evidence not found'], 404);
        }
        $filePath = $evidence->url;
        return response()->json(['url' => $filePath], 200, [], JSON_UNESCAPED_SLASHES);
    }
    public function createFirmware(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'name' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'required|file',
        ]);



        // Crear un nuevo registro de Firmware
        $firmware = new Firmware();
        $firmware->name = $request->name;
        $firmware->version = $request->version;
        $firmware->description = $request->description;
        $firmware->save();

        // Subir el archivo a S3 y guardar la información en la tabla Image
        $file = $request->file('file');
        $firmware->saveImageOnModelWithMorphMany($file, "evidences");


        // Devolver una respuesta exitosa
        return response()->json(['message' => 'Firmware created successfully', 'firmware' => $firmware], 201);
    }

}
