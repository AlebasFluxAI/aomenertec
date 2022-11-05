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
        if (Auth::check()) {
            dispatch(new ChangeRegisterJob($model, $model->getOriginal(), $model->getAttributes(), Change::CHANGE_TYPE_CREATED, Auth::user(), $model->getChanges()));
        }
    }

    public function updated(Model $model)
    {
        if (Auth::check()) {
            dispatch(new ChangeRegisterJob($model, $model->getOriginal(), $model->getAttributes(), Change::CHANGE_TYPE_UPDATED, Auth::user(), $model->getChanges()));
        }
    }

    public function deleted(Model $model)
    {
        if (Auth::check()) {
            dispatch(new ChangeRegisterJob($model, $model->getOriginal(), $model->getAttributes(), Change::CHANGE_TYPE_DELETED, Auth::user(), $model->getChanges()));
        }
    }
}
