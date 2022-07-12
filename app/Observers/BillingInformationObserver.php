<?php

namespace App\Observers;

use App\Models\V1\BillingInformation;

class BillingInformationObserver
{
    public function creating(BillingInformation $billingInformation)
    {
        if ($client = $billingInformation->client) {
            $client->billingInformation()->update([
                "default" => false
            ]);
        }
    }
}
