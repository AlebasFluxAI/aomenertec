<?php

namespace App\Jobs\V1\Enertec\Invoicing;

use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientType;
use App\Models\V1\EquipmentType;
use App\Models\V1\MicrocontrollerData;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ClientInvoicingJob implements ShouldQueue
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
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $monthlyConsumption = $this->client
            ->monthlyMicrocontrollerData->whereMonth(now()->month)
            ->first()
            ->interval_real_consumption;
        $total_value = $monthlyConsumption;
        $totalToPay = $this->client->consumptionFee() * $total_value;
    
    }
}
