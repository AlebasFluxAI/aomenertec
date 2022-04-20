<?php

namespace App\Observers\MicrocontrollerData;

<<<<<<< HEAD
use App\Models\V1\MicrocontrollerData;

class MicrocontrollerDataObserver
=======
<<<<<<< HEAD
use App\Models\V1\MicrocontrollerData;

class MicrocontrollerDataObserver
=======
use App\Events\NewPointDataMonitoringEvent;
use App\Models\V1\AnnualMicrocontrollerData;
use App\Models\V1\DailyMicrocontrollerData;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\MonthlyMicrocontrollerData;
use App\Models\V1\HourlyMicrocontrollerData;
use Illuminate\Contracts\Queue\ShouldQueue;
class MicrocontrollerDataObserver implements ShouldQueue
>>>>>>> 841826f7ca9fd2b0b887509f916d2701174f94cd
>>>>>>> develop_v2
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
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
    public function updated(MicrocontrollerData $microcontrollerData)
    {
        $microcontrollerData->intervalMiningData();
        event(new NewPointDataMonitoringEvent());
    }

>>>>>>> 841826f7ca9fd2b0b887509f916d2701174f94cd
>>>>>>> develop_v2
}
