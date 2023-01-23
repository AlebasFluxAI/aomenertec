<?php

use App\Models\V1\Pqr;
use App\Models\V1\PqrMessage;

return [
    Pqr::PQR_TYPE_TECHNICIAN => "Incidente tecnico",
    Pqr::PQR_TYPE_PLATFORM => "Problemas con la plataforma",
    Pqr::PQR_TYPE_BILLING => "Problemas de pagos y/o facturación",

    Pqr::PQR_SUB_TYPE_OVERRUN => "Sobrecosto de servicio",
    Pqr::PQR_SUB_TYPE_INVOICING => "Facturación",
    Pqr::PQR_SUB_TYPE_PAYMENT_AGREE => "Acuerdo de pago",
    Pqr::PQR_SUB_TYPE_PLATFORM_ADMIN => "Administración de plataforma",
    Pqr::PQR_SUB_TYPE_ERROR => "Inconveniente técnico",

    Pqr::PQR_SEVERITY_HIGH => "Prioridad alta",
    Pqr::PQR_SEVERITY_MEDIUM => "Prioridad media",
    Pqr::PQR_SEVERITY_LOW => "Prioridad baja",

    Pqr::STATUS_CREATED => "Ticket abierto",
    Pqr::STATUS_PROCESSING => "Ticket pendiente",
    Pqr::STATUS_RESOLVED => "Ticket resuelto",
    Pqr::STATUS_CLOSED => "Ticket cerrado",

    Pqr::PQR_LEVEL_1 => "Nivel 1",
    Pqr::PQR_LEVEL_2 => "Nivel 2",

    PqrMessage::SENDER_TYPE_CLIENT => "Cliente",
    PqrMessage::SENDER_TYPE_USER => "Usuario",
    PqrMessage::SENDER_TYPE_NETWORK_OPERATOR => "Operador de red",
    PqrMessage::SENDER_TYPE_SUPERVISOR => "Supervisor",

];
