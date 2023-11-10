<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Tax;

use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Tax\DetailsTaxService;
use App\Models\V1\Tax;
use Livewire\Component;
use function view;

class TaxDetailsComponent extends Component
{
    public $model;
    private $detailsTaxService;

    public function __construct($id = null)
    {
        $this->detailsTaxService = DetailsTaxService::getInstance();
        parent::__construct($id);
    }

    public function mount(Tax $tax)
    {
        $this->detailsTaxService->mount($this, $tax);
    }

    public function render()
    {
        return view('livewire.v1.admin.invoicing.tax.details-tax')->extends('layouts.v1.app');
    }

}
