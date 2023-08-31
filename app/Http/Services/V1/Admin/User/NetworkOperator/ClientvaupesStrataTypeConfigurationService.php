<?php

namespace App\Http\Services\V1\Admin\User\NetworkOperator;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Model\V1\BillingService;
use App\Models\Traits\NetworkOperatorPriceTrait;
use App\Models\V1\AdminConfiguration;
use App\Models\V1\Client;
use App\Models\V1\ClientType;
use App\Models\V1\NetworkOperator;
use App\Models\V1\PhotovoltaicPrice;
use App\Models\V1\Stratum;
use App\Models\V1\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ClientvaupesStrataTypeConfigurationService extends Singleton
{

}
