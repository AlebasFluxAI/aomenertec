<?php

namespace App\Observers\MicrocontrollerData;

use App\Models\V1\MicrocontrollerData;

class MicrocontrollerDataObserver
{
    /**
     * Handle the MicrocontrollerData "created" event.
     *
     * @param MicrocontrollerData $microcontrollerData
     * @return void
     */
    public function created(MicrocontrollerData $microcontrollerData)
    {
        $microcontrollerData->miningData();
    }

}
