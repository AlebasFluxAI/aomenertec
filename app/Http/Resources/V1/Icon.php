<?php

namespace App\Http\Resources\V1;

use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Icon extends Singleton
{
    public static function getIcon()
    {
        try {
            return Auth::user()->admin->icon->url;
        } catch (Throwable $e) {
            return "https://aom.enerteclatam.com/images/logo-horizontal.svg";
        }
    }

}
