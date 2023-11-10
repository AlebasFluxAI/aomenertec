<?php

namespace App\Http\Services\V1\Admin\Invoicing\Report;

use App\Http\Services\Singleton;
use App\Http\Services\V1\Admin\Client\AddClient;
use App\Models\V1\Admin;
use App\Models\V1\Invoice;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Livewire\Component;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Facades\Excel;

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
