<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Tax;

use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Tax\AddTaxService;
use Livewire\Component;
use function view;

class TaxAddComponent extends Component
{
    public $name;
    public $description;
    public $percentage;


    private $addTaxService;

    public function __construct($id = null)
    {
        $this->addTaxService = AddTaxService::getInstance();
        parent::__construct($id);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.invoicing.tax.add-tax')->extends('layouts.v1.app');
    }

    public function submitForm()
    {
        return $this->addTaxService->submitForm($this);
    }
}
