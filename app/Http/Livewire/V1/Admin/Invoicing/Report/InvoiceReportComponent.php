<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Report;

use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Report\ReportInvoiceService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class InvoiceReportComponent extends Component
{
    use WithPagination;
    use FilterTrait;


    public $months;
    private $reportInvoicetemsService;

    public function __construct($id = null)
    {
        $this->reportInvoicetemsService = ReportInvoiceService::getInstance();
        parent::__construct($id);
    }


    public function mount()
    {
        return $this->reportInvoicetemsService->mount($this);
    }

    public function generateReport($month)
    {
        return $this->reportInvoicetemsService->generateReport($this, $month);

    }

    public function render()
    {
        return view(
            'livewire.v1.admin.invoicing.report.report-invoice'
        )->extends('layouts.v1.app');
    }


}
