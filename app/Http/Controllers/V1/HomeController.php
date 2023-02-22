<?php

namespace App\Http\Controllers\V1;

use App\Models\V1\ClientAlert;
use App\Notifications\Alert\AlertControlNotification;
use App\Notifications\Alert\AlertNotification;
use Illuminate\Http\Request;
use App\Models\V1\User;

class HomeController extends Controller
{
    public function index()
    {
        return redirect('/v1/inicio');
    }

    public function healthCheck()
    {
        $user = User::whereEmail("wilder.herrera@unillanos.edu.co")->first();
        $user->notifyNow(new AlertControlNotification(ClientAlert::first(), "alert_control_successs"));
        return;
    }
}
