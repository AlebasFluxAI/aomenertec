<?php

namespace App\Http\Livewire\V1\Admin\Purchase;

use App\Http\Services\V1\Admin\User\Purchase\PurchaseGuestCreateService;
use App\Models\Traits\CreateRechargeTrait;
use Livewire\Component;
use function view;

class PurchaseGuestCreateComponent extends Component
{
    use CreateRechargeTrait;

    public const PURCHASE_TYPE_KWH = "kwh";
    public const PURCHASE_TYPE_CASH = "cash";
    private $purchaseGuestCreateService;

    public function __construct($id = null)
    {
        $this->purchaseGuestCreateService = PurchaseGuestCreateService::getInstance();
        parent::__construct($id);
    }


    public function updatedPurchaseType()
    {
        $this->purchaseGuestCreateService->updatedPurchaseType($this);
    }

    public function updatedKwhQuantity()
    {
        $this->purchaseGuestCreateService->updatedKwhQuantity($this);
    }

    public function updatedTotal()
    {
        $this->purchaseGuestCreateService->updatedTotal($this);
    }

    public function confirmRecharge()
    {
        $this->purchaseGuestCreateService->confirmRecharge($this);
    }


    public function mount()
    {
        $this->purchaseGuestCreateService->mount($this);
    }

    public function submitForm()
    {
        $this->purchaseGuestCreateService->submitForm($this);
    }

    public function render()
    {
        return view(
            'livewire.v1.admin.purchase.guest-create-purchase'
        )->extends('layouts.v1.app', ["without_header" => true]);
    }
}
