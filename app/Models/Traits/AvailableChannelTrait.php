<?php

namespace App\Models\Traits;

use App\Models\V1\AvailableChannel;

trait AvailableChannelTrait
{
    public function channels()
    {
        return $this->morphMany(AvailableChannel::class, "model");
    }
}
