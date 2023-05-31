<?php

namespace App\Http\Controllers\V1;

use App\Http\Resources\V1\Menu;
use App\Jobs\V1\Enertec\Report\ClientReportSendJob;
use App\Models\V1\BillableItem;
use App\Models\V1\Client;
use App\Models\V1\ClientAlert;
use App\Models\V1\ClientType;
use App\Models\V1\ZniLevelFee;
use App\Notifications\Alert\AlertControlNotification;
use App\Notifications\Alert\AlertNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\V1\User;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        return redirect('/v1/inicio');
    }

    public function healthCheck()
    {
        $client = Client::first();
        $client->update([
            "updated_at" => now()
        ]);
        return response()->json("", 200);
    }
}
