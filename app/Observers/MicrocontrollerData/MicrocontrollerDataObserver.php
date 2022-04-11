<?php

namespace App\Observers\MicrocontrollerData;

use App\Events\NewPointDataMonitoringEvent;
use App\Models\V1\MicrocontrollerData;
use Illuminate\Contracts\Queue\ShouldQueue;
class MicrocontrollerDataObserver implements ShouldQueue
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
        event(new NewPointDataMonitoringEvent());
    }

}
