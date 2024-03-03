<?php

namespace App\Http\Controllers\V1\ConfigurationClient;


use App\Http\Controllers\V1\Controller;
use App\Http\Services\V1\ConfigurationClient\ConfigurationClientService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationClientController extends Controller
{
    protected $configurationClientService;

    public function __construct(ConfigurationClientService $configurationClientService)
    {
        $this->configurationClientService = $configurationClientService;
    }

    /**
     * Enviar mensaje para ON/OFF medidor.
     *
     * @OA\Get(
     *     path="/v1/config/set-status-coil",
     *     operationId="setStatusCoilForSerial",
     *     tags={"Corte/Conexion suministro electrico"},
     *     summary="Accionar suministro electrico de medidor por medio del serial",
     *     security={
     *         {"api_key_security_example": {}}
     *     },
     *     @OA\Parameter(
     *         name="serial",
     *         in="query",
     *         description="Número de serie del equipo",
     *         required=true,
     *
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Accion a realizar 1->activar suministro, 0->desactivar suministro",
     *         required=true,
     *     ),
     *        @OA\Response(
     *          response=200,
     *          description="Respuesta exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="message", type="string"),
     *                  @OA\Property(property="detail", type="string"),
     *                  @OA\Property(property="serial", type="string"),
     *                  @OA\Property(property="ack_log_id", type="number"),
     *                  @OA\Property(property="event_id", type="number"),
     *
     *                  )
     *          )
     *      ),
     *     @OA\Response(
     *         response=400,
     *         description="Solicitud incorrecta",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="details", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="details", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="code", type="integer"),
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="details", type="string")
     *         )
     *     )
     * )
     */
    public function setStatusCoilForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setStatusCoilForSerial($request);
    }

    public function getStatusCoilForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->getStatusCoilForSerial($request);
    }

    public function getDateForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->getDateForSerial($request);
    }

    public function setDateForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setDateForSerial($request);
    }

    public function getTypeSensorForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->getTypeSensorForSerial($request);
    }

    public function setTypeSensorForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->setTypeSensorForSerial($request);
    }

    public function getStatusSensorForSerial(Request $request): JsonResource
    {
        return $this->configurationClientService->getStatusSensorForSerial($request);
    }

    public function notificationWebhook(Request $request)
    {
        $datosJson = $request->json()->all();

        $responseData = [
            'status' => 'success',
            'message' => 'Webhook procesado exitosamente',
            'request_json' => $datosJson
        ];

        // Retornar una instancia de Response con los datos y código de estado apropiados
        return response()->json($responseData, 200);
    }

}
