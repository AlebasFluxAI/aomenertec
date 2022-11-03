<?php

namespace App\Observers\MicrocontrollerData;

use App\Events\NewPointDataMonitoringEvent;
use App\Jobs\V1\Enertec\SerializeMicrocontrollerDataJob;
use App\Jobs\V1\Enertec\UpdatedMicrocontrollerDataJob;
use App\Models\V1\AuxData;
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
        //AuxData::create([
          //  'data' => $microcontrollerData->raw_json
        //]);
    }

    public function updated(MicrocontrollerData $microcontrollerData)
    {
        dispatch(new SerializeMicrocontrollerDataJob($microcontrollerData));

        //$microcontrollerData->jsonEdit();
    }
}
