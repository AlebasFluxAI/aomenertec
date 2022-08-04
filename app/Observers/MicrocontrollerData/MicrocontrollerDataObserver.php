<?php

namespace App\Observers\MicrocontrollerData;

use App\Events\NewPointDataMonitoringEvent;
use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\MicrocontrollerData;

class MicrocontrollerDataObserver
{
    /**
     * Handle the MicrocontrollerData "created" event.
     *
     * @param MicrocontrollerData $microcontrollerData
     * @return void
     */

    public function updated(MicrocontrollerData $microcontrollerData)
    {
        $microcontrollerData->jsonEdit();
        UpdatedMicrocontrollerDataJob::dispatch($microcontrollerData);

    }
}
