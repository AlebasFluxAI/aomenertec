<?php

use App\Models\V1\WorkOrder;

return [
    WorkOrder::WORK_ORDER_TYPE_RECONNECTION => "Reconexión",
    WorkOrder::WORK_ORDER_TYPE_DISCONNECTION => "Desconexión",
    WorkOrder::WORK_ORDER_TYPE_READING => "Lectura",
    WorkOrder::WORK_ORDER_TYPE_INSTALLATION => "Instalacion",
    WorkOrder::WORK_ORDER_TYPE_REPLACE => "Cambio de equipo",
    WorkOrder::WORK_ORDER_TYPE_CORRECTIVE_MAINTENANCE => "Mantenimiento correctivo",
    WorkOrder::WORK_ORDER_TYPE_PREVENTIVE_MAINTENANCE => "Mantenimiento preventivo",
    WorkOrder::WORK_ORDER_STATUS_OPEN => "Abierta",
    WorkOrder::WORK_ORDER_STATUS_IN_PROGRESS => "En progreso",
    WorkOrder::WORK_ORDER_STATUS_CLOSED => "Cerrada",
    WorkOrder::WORK_ORDER_TYPE_DISABLE_CLIENT => "Desactivar / Activar cliente",
    WorkOrder::WORK_ORDER_TYPE_ENABLE_CLIENT => "Activar cliente",
    WorkOrder::WORK_ORDER_TYPE_PQR => "Creada desde PQR",
    WorkOrder::WORK_ORDER_LEVEL_1 => "Nivel 1",
    WorkOrder::WORK_ORDER_LEVEL_2 => "Nivel 2",
];
