<?php

namespace App\Observers\User\NetworkOperator;

use App\Models\V1\NetworkOperator;

class UserNetworkOperatorObserver
{
    public function creating(NetworkOperator $networkOperator)
    {
        $user = $networkOperator->user;
        if (!$user) {
            return;
        }

        $networkOperator->email = $user->email;
        $networkOperator->name = $user->name;
        $networkOperator->last_name = $user->last_name;
        $networkOperator->phone = $user->phone;
        $networkOperator->identification = $user->identification;
    }

    public function updated(NetworkOperator $networkOperator)
    {
        $user = $networkOperator->user;
        if (!$user) {
            return;
        }

        $user->update([
            "name" => $networkOperator->name,
            "last_name" => $networkOperator->last_name,
            "email" => $networkOperator->email,
            "phone" => $networkOperator->phone,
            "identification" => $networkOperator->identification,
        ]);
    }
}
