<?php

namespace App\Http\Services\V1\Admin\Client;

use App\Http\Resources\V1\IndicativeHelper;
use App\Http\Resources\V1\TimeZoneHelper;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Http\Resources\V1\Icon;
use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\Singleton;
use App\Models\Traits\ClientServiceTrait;
use App\Models\V1\Admin;
use App\Models\V1\BillingInformation;
use App\Models\V1\ClientSupervisor;
use App\Models\V1\EquipmentClient;
use App\Models\V1\ClientType;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Import;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\Municipality;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Client;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Reader;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class ClientImportIndexService extends Singleton
{
    use ClientServiceTrait;

    public function getData()
    {
        $admin = Auth::user()->getAdmin();
        if (User::getUserModel()::class == Admin::class) {
            return Import::whereIn("auditable_id", array_merge($admin->networkOperators()->pluck('id')->toArray(), [$admin->id]))->paginate();
        }
        if (User::getUserModel()::class == NetworkOperator::class) {
            return Import::whereAuditableId(Auth::user()->id)->paginate();
        }
        if (User::getUserModel()::class == SuperAdmin::class) {

            return Import::paginate();
        }
        return [];

    }

}
