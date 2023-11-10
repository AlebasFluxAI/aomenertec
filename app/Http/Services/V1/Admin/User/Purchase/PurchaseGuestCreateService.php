<?php

namespace App\Http\Services\V1\Admin\User\Purchase;

use App\Http\Services\Singleton;
use App\Models\Traits\CreateRechargeTrait;
use App\Models\V1\ClientRecharge;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseGuestCreateService extends Singleton
{
    use CreateRechargeTrait;


    public function confirmRecharge(Component $component)
    {
        $component->recharge_code = $this->createRechargeCode($component);
        DB::transaction(function () use ($component) {
            ClientRecharge::create([
                "client_id" => $component->client->id,
                "network_operator_id" => $component->networkOperator->id,
                "kwh_price" => $component->price->price,
                "kwh_credit" => $component->price->credit,
                "kwh_subsidy" => $component->price->subsidy,
                "kwh_quantity" => $component->kwh_quantity,
                "total" => $component->total,
                "reference" => $component->reference,
                "status" => ClientRecharge::PURCHASE_PAYMENT_STATUS_PENDING,
                "recharge_code" => $component->recharge_code,
                "consecutive" => $component->client->lastConsecutiveRecharge() + 1
            ]);
        });
    }

}
