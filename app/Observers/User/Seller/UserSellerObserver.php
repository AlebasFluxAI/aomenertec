<?php

namespace App\Observers\User\Seller;

use App\Models\V1\Seller;

class UserSellerObserver
{
    public function creating(Seller $seller)
    {
        $user = $seller->user;
        if (!$user) {
            return;
        }
        $seller->email = $user->email;
        $seller->name = $user->name;
        $seller->last_name = $user->last_name;
        $seller->phone = $user->phone;
        $seller->identification = $user->identification;
    }

    public function updated(Seller $seller)
    {
        $user = $seller->user;
        if (!$user) {
            return;
        }

        $user->update([
            "name" => $seller->name,
            "last_name" => $seller->last_name,
            "email" => $seller->email,
            "phone" => $seller->phone,
            "identification" => $seller->identification,
        ]);
    }
}
