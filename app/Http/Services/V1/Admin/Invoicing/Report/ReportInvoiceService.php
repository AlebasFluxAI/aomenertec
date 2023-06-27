<?php

namespace App\Http\Services\V1\Admin\Invoicing\Report;

use App\Exports\V1\MultipleSheetsMonitoringData;
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
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;
use function auth;
use function bcrypt;
use function session;

class ReportInvoiceService extends Singleton
{

    public function mount(Component $component)
    {
        $model = User::getUserModel();
        if ($model::class == NetworkOperator::class) {
            $admin_id = $model->admin_id;
            $invoices = Invoice::whereType(Invoice::TYPE_CONSUMPTION)->whereAdminId($admin_id)->orderBy("created_at", "desc")->get();
        }
        if ($model::class == Admin::class) {
            $invoices = Invoice::whereType(Invoice::TYPE_CONSUMPTION)->whereAdminId($model->id)->orderBy("created_at", "desc")->get();
        }
        $dates = $invoices->pluck("created_at");

        $component->months = collect($dates->map(function ($value) {
            return new Month($value->month);
        }))->unique();
    }

    public function generateReport(Component $component, $month)
    {
        $component->months = collect($component->months->unique()->map(function ($value) {
            return new Month($value[array_keys($value)[0]]);
        }));

        return Excel::download(new InvoiceReportData($month), 'data.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

}

class InvoicesExport implements FromArray
{
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function array(): array
    {
        return $this->invoices;
    }
}

class Month
{
    public $month;

    public function __construct($month)
    {
        $this->month = $month;
    }
}
