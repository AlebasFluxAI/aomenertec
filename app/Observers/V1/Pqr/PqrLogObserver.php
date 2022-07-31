<?php

namespace App\Observers\V1\Pqr;

use App\Models\V1\Pqr;
use App\Models\V1\PqrLog;

class PqrLogObserver
{
    public function created(Pqr $pqr)
    {
        $pqr->logs()->create([
            "activity_type" => PqrLog::ACTIVITY_TYPE_OPEN_TICKET,
        ]);
    }

    public function updated(Pqr $pqr)
    {
        $activityType = match ($pqr->status) {
            Pqr::STATUS_PROCESSING => PqrLog::ACTIVITY_TYPE_OPEN_TICKET,
            Pqr::STATUS_CLOSED => PqrLog::ACTIVITY_TYPE_CLOSE_TICKET,
            default => PqrLog::ACTIVITY_TYPE_CHANGE_LEVEL,
        };
        if ($pqr->isDirty("level")) {
            $activityType = PqrLog::ACTIVITY_TYPE_CHANGE_LEVEL;
        }
        $pqr->logs()->create([
            "activity_type" => $activityType,
        ]);
    }
}
