<?php

namespace App\Models\Traits;

use App\Models\V1\AvailableChannel;
use App\Models\V1\Change;

trait AvailableChannelTrait
{
    public function channels()
    {
        return $this->morphMany(AvailableChannel::class, "model");
    }
}
