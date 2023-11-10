<?php

namespace App\Jobs\V1;

use App\Models\V1\EquipmentType;
use App\Models\V1\StopUnpackDataClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetClientStopUnpackDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $json;

    public function __construct($json)
    {
        $this->json = $json;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $equipment_serial = str_pad($this->json['did'], 6, "0", STR_PAD_LEFT);
        $equipment = EquipmentType::find(1)->equipment()->whereSerial($equipment_serial)
            ->first();
        if ($equipment) {
            $client = $equipment->clients()->first();
            if ($client) {
                if ($this->json['frame_save']) {
                    if (!$client->stopUnpackClient()->exists()) {
                        StopUnpackDataClient::create(['client_id' => $client->id]);
                    }
                } else {
                    if ($client->stopUnpackClient()->exists()) {
                        $client->stopUnpackClient->delete();
                    }
                }
            }
        }
    }
}
