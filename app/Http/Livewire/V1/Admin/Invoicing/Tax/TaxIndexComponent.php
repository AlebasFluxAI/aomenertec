<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Tax;

use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Tax\IndexTaxService;
use App\Models\Traits\FilterTrait;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class TaxIndexComponent extends Component
{
    use WithPagination;
    use FilterTrait;


    private $indexTaxService;

    public function __construct($id = null)
    {
        $this->indexTaxService = IndexTaxService::getInstance();
        parent::__construct($id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.invoicing.tax.index-tax',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->indexTaxService->getData($this);
    }
}
