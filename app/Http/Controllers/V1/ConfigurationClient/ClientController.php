<?php

namespace App\Http\Controllers\V1\ConfigurationClient;

use App\Http\Controllers\V1\Controller;
use App\Http\Services\V1\ConfigurationClient\ClientService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientController extends Controller
{
    protected $clientService;

    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Agregar cliente con serial medidor.
     *
     * @OA\Post (
     *     path="/v1/clients/add",
     *     operationId="addClient",
     *     tags={"Agregar cliente"},
     *     summary="Agregar cliente por medio de serial del medidor electrico",
     *     security={
     *         {"api_key_security_example": {}}
     *     },
     *     @OA\Parameter(
     *         name="serial",
     *         in="query",
     *         description="Número de serie del equipo",
     *         required=true,
     *     ),
     *        @OA\Response(
     *          response=200,
     *          description="Respuesta exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *
     *                  @OA\Property(property="equipment", type="object"),
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
    public function addClient(Request $request): JsonResource
    {
        return $this->clientService->addClient($request);
    }

    /**
     * Obtener seriales disponibles por coincidencia.
     *
     * @OA\Get (
     *     path="/v1/equipment/get-list",
     *     operationId="getEquipmentForType",
     *     tags={"Obtener equipos disponibles"},
     *     summary="El servicio obtiene una lista con los seriales disponibles que coincidad con el filtro ingresado en el parametro serial",
     *     security={
     *         {"api_key_security_example": {}}
     *     },
     *      @OA\Parameter(
     *         name="serial",
     *         in="query",
     *         description="Número de serie del equipo",
     *     ),
     *        @OA\Response(
     *          response=200,
     *
     *          description="Respuesta exitosa",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *
     *                  @OA\Property(property="serial", type="string"),
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
    public function getEquipmentForType(Request $request): JsonResource
    {
        return $this->clientService->getEquipmentForType($request);
    }

    public function addEquipment(Request $request): JsonResource
    {
        return $this->clientService->addEquipment($request);
    }

}
