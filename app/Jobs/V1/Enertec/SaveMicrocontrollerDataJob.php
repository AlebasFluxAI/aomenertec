<?php

namespace App\Jobs\V1\Enertec;

use App\Models\V1\MicrocontrollerData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SaveMicrocontrollerDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $raw_json;

    public function __construct($raw_json)
    {
        $this->raw_json = $raw_json;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        MicrocontrollerData::create([
            "raw_json" => $this->raw_json
        ]);
    }
}
