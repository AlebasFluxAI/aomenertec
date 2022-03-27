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

class Style extends Singleton
{

    public static function getStyle()
    {
        try {
            return "assets/css/" . Auth::user()->admin->css_file . '.css';
        } catch (Throwable $e) {
            return "assets/css/style.css";
        }

    }

}
