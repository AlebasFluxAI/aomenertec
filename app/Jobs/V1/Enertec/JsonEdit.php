<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\MicrocontrollerData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class JsonEdit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $model;
    public $flag;

    public function __construct(MicrocontrollerData $model, $flag)
    {
        $this->model = $model->withoutRelations();
        $this->flag = $flag;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->model->jsonEdit($this->flag);
    }
}
