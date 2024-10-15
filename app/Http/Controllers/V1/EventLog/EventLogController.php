<?php

namespace App\Http\Controllers\V1\EventLog;

use App\Http\Services\V1\EventLog\EventLogService;
use App\Models\V1\Api\AckLog;
use App\Models\V1\Api\EventLog;

class EventLogController
{

    protected $eventLogService;

    public function __construct(EventLogService $eventLogService)
    {
        $this->eventLogService = $eventLogService;
    }

    /**
     * Obtener seriales disponibles por coincidencia.
     *
     * @OA\Get (
     *     path="/v1/event_logs/{id}",
     *     operationId="getEventLogById",
     *     tags={"Obtener informacion de un evento basado en Id"},
     *     summary="El endpoint toma el id del evento enviado en el path y retorna la informacion de este",
     *     security={
     *         {"api_key_security_example": {}}
     *     },
     *      @OA\Parameter(
     *         name="serial",
     *         in="path",
     *         description="Identificador del evento",
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
    public function getEventLogById(EventLog $eventLog)
    {
        return $this->eventLogService->getEventLogById($eventLog);
    }

    /**
     * Obtener seriales disponibles por coincidencia.
     *
     * @OA\Get (
     *     path="/v1/event_logs/",
     *     operationId="getEventLogs",
     *     tags={"Obtiene una coleccion de eventos en base a filtros"},
     *     summary="El endpoint toma el filtrs del evento enviado en el path y retorna la informacion de este",
     *     security={
     *         {"api_key_security_example": {}}
     *     },
     * @OA\Parameter(
     *          name="s_f",
     *          in="path",
     *          description="Field to seach by text",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="s",
     *          in="path",
     *          description="String value for searching",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="o_b",
     *          in="path",
     *          description="Method to order ASC or DESC",
     *          required=false,
     *          @OA\Schema(type="string")
     *      ),
     *      @OA\Parameter(
     *          name="o_f",
     *          in="path",
     *          description="Field to order",
     *          required=false,
     *          @OA\Schema(type="string")
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
    public function getEventLogs()
    {
        return $this->eventLogService->getEventLogs();
    }

    /**
     * Obtener seriales disponibles por coincidencia.
     *
     * @OA\Get (
     *     path="/v1/event_logs/ack_logs/{ack_log_id}",
     *     operationId="getEventLogByAckLog",
     *     tags={"Obtiene una coleccion de eventos en base a un identificador de ack log"},
     *     summary="El endpoint toma el filtrs del evento enviado en el path y retorna la informacion de este",
     *     security={
     *         {"api_key_security_example": {}}
     *     },
     * @OA\Parameter(
     *          name="ack_log_id",
     *          in="path",
     *          description="Field to seach by text",
     *          required=false,
     *          @OA\Schema(type="string")
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

    public function getEventLogByAckLog(AckLog $ackLog)
    {
        return $this->eventLogService->getEventLogByAckLog($ackLog);
    }
}
