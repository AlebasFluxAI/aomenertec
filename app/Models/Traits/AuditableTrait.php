<?php

namespace App\Models\Traits;

use App\Models\V1\Change;

trait AuditableTrait
{
    public function auditory()
    {
        return $this->morphMany(Change::class, "model");
    }
}
