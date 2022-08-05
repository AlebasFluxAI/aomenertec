<?php

namespace App\Jobs;

use App\Models\V1\Change;
use App\Models\V1\User;
use App\Observers\V1\Change\ChangeObserver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ChangeRegisterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $model;
    private $type;
    private $user;

    public function __construct(Model $model, $type, User $user)
    {
        $this->model = $model;
        $this->type = $type;
        $this->user = $user;
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
                $this->createChange(Change::CHANGE_TYPE_CREATED, $this->model);
                break;
            case(Change::CHANGE_TYPE_UPDATED):
                $this->createChange(Change::CHANGE_TYPE_UPDATED, $this->model);
                break;
            case(Change::CHANGE_TYPE_DELETED):
                $this->createChange(Change::CHANGE_TYPE_DELETED, $this->model);
                break;
            default:
                break;
        }

    }

    private function createChange($type, $model)
    {

        DB::table("changes")->insert([
            "before" => json_encode($model->getOriginal()),
            "after" => $model->toJson(),
            "delta" => json_encode($model->getChanges()),
            "user_id" => $this->user->id,
            "type" => $type,
            "model_id" => $model->id,
            "model_type" => $model::class,
        ]);
    }

}
