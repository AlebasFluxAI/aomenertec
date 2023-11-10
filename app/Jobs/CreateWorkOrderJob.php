<?php

namespace App\Jobs;

use App\Models\V1\User;
use App\Models\V1\WorkOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateWorkOrderJob implements ShouldQueue
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
    private $pqr;
    private $user_id;


    public function __construct($pqr, $user_id)
    {
        $this->pqr = $pqr;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->pqr->workOrder()->exists()) {
            return;
        }
        $this->pqr->client->workOrders()->create([
            "description" => $this->pqr->detail,
            "technician_id" => $this->pqr->technician_id,
            "type" => WorkOrder::WORK_ORDER_TYPE_REPLACE,
            "pqr_id" => $this->pqr->id,
            "created_by_id" => $this->user_id,
            "created_by_type" => User::class,
        ]);
    }
}
