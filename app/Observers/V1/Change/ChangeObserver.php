<?php

namespace App\Observers\V1\Change;

use App\Jobs\ChangeRegisterJob;
use App\Models\V1\Change;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChangeObserver
{


    public function created(Model $model)
    {
        dispatch(new ChangeRegisterJob($model, Change::CHANGE_TYPE_CREATED, Auth::user()));
    }

    public function updated(Model $model)
    {
        dispatch(new ChangeRegisterJob($model, Change::CHANGE_TYPE_UPDATED, Auth::user()));
    }

    public function deleted(Model $model)
    {
        dispatch(new ChangeRegisterJob($model, Change::CHANGE_TYPE_DELETED, Auth::user()));
    }
}
