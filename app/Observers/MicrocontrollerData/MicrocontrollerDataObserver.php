<?php

namespace App\Observers\MicrocontrollerData;

use App\Events\NewPointDataMonitoringEvent;
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
    public function updated(MicrocontrollerData $microcontrollerData)
    {
        $microcontrollerData->IntervalMiningData();
        //event(new NewPointDataMonitoringEvent());
    }
}
