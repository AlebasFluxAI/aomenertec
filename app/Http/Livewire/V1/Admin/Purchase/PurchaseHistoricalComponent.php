<?php

namespace App\Http\Livewire\V1\Admin\Purchase;

use App\Http\Services\V1\Admin\User\Purchase\PurchaseHistoricalService;
use App\Models\V1\Seller;
use Livewire\Component;
use Livewire\WithPagination;
use function view;

class PurchaseHistoricalComponent extends Component
{
    use WithPagination;

    public $model;
    private $purchaseHistoricalService;

    public function __construct($id = null)
    {
        $this->purchaseHistoricalService = PurchaseHistoricalService::getInstance();
        parent::__construct($id);
    }

    public function mount(Seller $seller)
    {
        $this->purchaseHistoricalService->mount($this, $seller);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.purchase.historical-purchase',
            [
                "data" => $this->getData()
            ]
        )->extends('layouts.v1.app');
    }

    public function getData()
    {
        return $this->purchaseHistoricalService->getData($this);
    }
}
