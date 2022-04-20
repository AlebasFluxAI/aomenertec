<?php

namespace App\Observers\MicrocontrollerData;

use App\Models\V1\MicrocontrollerData;

use App\Events\NewPointDataMonitoringEvent;
use App\Models\V1\AnnualMicrocontrollerData;
use App\Models\V1\DailyMicrocontrollerData;
use App\Models\V1\MonthlyMicrocontrollerData;
use App\Models\V1\HourlyMicrocontrollerData;
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
        $microcontrollerData->intervalMiningData();
        event(new NewPointDataMonitoringEvent());
    }

}
