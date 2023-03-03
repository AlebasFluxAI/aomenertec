<?php

namespace App\Http\Controllers\V1;

use App\Jobs\V1\Enertec\Report\ClientReportSendJob;
use App\Models\V1\Client;
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
        dispatch(new ClientReportSendJob(Client::MONTHLY_RATE));
        return;
    }
}
