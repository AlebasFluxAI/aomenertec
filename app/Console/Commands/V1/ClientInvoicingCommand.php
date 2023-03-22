<?php

namespace App\Console\Commands\V1;

use App\Jobs\V1\Enertec\Invoicing\ClientInvoicingJob;
use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClientInvoicingCommand implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (Client::get() as $client) {
            if (now()->subDay()->day == $client->clientConfiguration->billing_day) {
                dispatch(new ClientInvoicingJob($client));
            }

        }

    }
}
