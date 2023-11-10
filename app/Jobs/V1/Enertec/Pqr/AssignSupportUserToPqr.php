<?php

namespace App\Jobs\V1\Enertec\Pqr;

use App\Models\V1\Pqr;
use App\Models\V1\PqrUser;
use App\Models\V1\Support;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AssignSupportUserToPqr implements ShouldQueue
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
    public $pqr;

    public function __construct(Pqr $pqr)
    {
        $this->pqr = $pqr;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (Support::get() as $support) {
            $this->pqr->pqrUsers()->create([
                "user_id" => $support->user_id,
                "status" => PqrUser::STATUS_ENABLED
            ]);
        }
    }

}
