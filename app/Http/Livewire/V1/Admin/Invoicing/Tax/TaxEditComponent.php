<?php

namespace App\Http\Livewire\V1\Admin\Invoicing\Tax;

use App\Http\Services\V1\Admin\Invoicing\IndexInvoicingService;
use App\Http\Services\V1\Admin\Invoicing\Tax\EditTaxService;
use App\Models\V1\Tax;
use Livewire\Component;
use function view;

class TaxEditComponent extends Component
{
    public $model;
    public $name;
    public $description;
    public $percentage;
    private $editTaxService;

    public function __construct($id = null)
    {
        $this->editTaxService = EditTaxService::getInstance();
        parent::__construct($id);
    }

    public function mount(Tax $tax)
    {
        $this->editTaxService->mount($this, $tax);
    }

    public function render()
    {
        return view('livewire.v1.admin.invoicing.tax.edit-tax')->extends('layouts.v1.app');
    }

    public function submitForm()
    {
        return $this->editTaxService->submitForm($this);
    }


}
