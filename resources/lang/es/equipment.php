<?php

use App\Models\V1\Equipment;

return [
    Equipment::STATUS_REPAIR => "En reparación",
    Equipment::STATUS_REPAIRED => "Reparado",
    Equipment::STATUS_NEW => "Nuevo",
    Equipment::STATUS_DISREPAIR => "Dado de baja",
    Equipment::STATUS_REPAIR_PENDING => "Pendiente de reparación"
];
