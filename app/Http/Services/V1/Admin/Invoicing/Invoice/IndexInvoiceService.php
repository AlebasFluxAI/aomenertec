<?php

namespace App\Http\Services\V1\Admin\Invoicing\Invoice;

use App\Http\Resources\V1\ToastEvent;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Http\Services\Singleton;
use App\Models\V1\Admin;
use App\Models\V1\BillableItem;
use App\Models\V1\ClientTechnician;
use App\Models\V1\EquipmentClient;
use App\Models\V1\ClientType;
use App\Models\V1\Department;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Invoice;
use App\Models\V1\Location;
use App\Models\V1\LocationType;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\Municipality;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Seller;
use App\Models\V1\Stratum;
use App\Models\V1\SubsistenceConsumption;
use App\Models\V1\Client;
use App\Models\V1\Supervisor;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Models\V1\VoltageLevel;
use App\Scope\PaginationScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class IndexInvoiceService extends Singleton
{

    public function mount(Component $component)
    {
        $component->filterCol = "type";
        $component->filter = Invoice::TYPE_PLATFORM_USAGE;
    }

    public function setFilter(Component $component, $filterValue)
    {
        $component->filterCol = "type";
        $component->filter = $filterValue;

    }


    public function getData(Component $component)
    {
        $model = User::getUserModel();
        if ($model::class == NetworkOperator::class) {

            if ($component->filter) {
                return Invoice::whereNetworkOperatorId($model->id)->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
            }
            return Invoice::whereNetworkOperatorId($model->id)->pagination();
        }
        if ($model::class == Admin::class) {
            if ($component->filter) {
                return Invoice::whereAdminId($model->id)->where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
            }
            return Invoice::whereAdminId($model->id)->pagination();
        }
        if ($component->filter) {
            return Invoice::where($component->filterCol, 'ilike', '%' . $component->filter . '%')->pagination();
        }

        return Invoice::pagination();
    }


}
