<?php

namespace App\Observers\Driver;

use App\Models\V1\PqrMessage;

class PqrMessageObserver
{
    public function created(PqrMessage $pqrMessage)
    {
        $pqrMessage->buildOneImage(["image"]);
    }
}
