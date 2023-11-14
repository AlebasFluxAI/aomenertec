<?php

namespace App\Jobs;

use App\Models\V1\Change;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ChangeRegisterJob implements ShouldQueue
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
    private $model;
    private $before;
    private $after;
    private $type;
    private $user;
    private $changes;

    public function __construct($model, $before, $after, $type, $user, $changes)
    {
        $this->model = $model;
        $this->before = $before;
        $this->after = $after;
        $this->type = $type;
        $this->user = $user;
        $this->changes = $changes;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->type) {
            case(Change::CHANGE_TYPE_CREATED):
                $this->createChange($this->model, $this->before, $this->after, Change::CHANGE_TYPE_CREATED, $this->changes);
                break;
            case(Change::CHANGE_TYPE_UPDATED):
                $this->createChange($this->model, $this->before, $this->after, Change::CHANGE_TYPE_UPDATED, $this->changes);
                break;
            case(Change::CHANGE_TYPE_DELETED):
                $this->createChange($this->model, $this->before, $this->after, Change::CHANGE_TYPE_DELETED, $this->changes);
                break;
            default:
                break;
        }
    }

    private function createChange($model, $before, $after, $type, $changes)
    {
        DB::table("changes")->insert([
            "before" => json_encode($before),
            "after" => json_encode($after),
            "delta" => json_encode($changes),
            "user_id" => $this->user->id,
            "type" => $type,
            "model_id" => $model->id,
            "model_type" => $model::class,
        ]);
    }
}
