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
        //AuxData::create([
        //  'data' => $microcontrollerData->raw_json
        //]);
    }

    public function updated(MicrocontrollerData $microcontrollerData)
    {
        $microcontrollerData->jsonEdit(true);
    }
}
