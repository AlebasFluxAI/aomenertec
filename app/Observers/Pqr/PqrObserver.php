<?php

namespace App\Observers\Pqr;

use App\Models\V1\Client;
use App\Models\V1\Pqr;
use App\Models\V1\PqrMessage;
use App\Models\V1\PqrUser;
use App\Models\V1\Support;
use App\Notifications\Alert\PqrUpdatedNotification;
use Illuminate\Support\Facades\Auth;

class PqrObserver
{
    public function creating(Pqr $pqr)
    {
        $pqr->status = Pqr::STATUS_CREATED;
        $pqr->code = rand(100000, 999999);
        if (!$client = $this->getClient($pqr)) {
            return;
        }
        if ($client) {
            $pqr->client_id = $client->id;
            if ($pqr->client->technician->first()) {
                $pqr->technician_id = $client->technician->first()->technician->id;
                return;
            }
            $pqr->network_operator_id = $pqr->client->network_operator_id;
        }

    }


    private function getClient(Pqr $pqr)
    {

        if ($client_code = $pqr->client_code) {
            return Client::whereCode($client_code)->first();
        }

        return Client::whereIdentification($pqr->identification)->first();
    }

    public function updated(Pqr $pqr)
    {
        $this->sendChangeNotification($pqr);
    }

    private function sendChangeNotification(Pqr $pqr)
    {
        if ($pqr->isDirty("status")) {
            if ($pqr->client and $pqr->client->networkOperator) {
                $pqr->client->networkOperator->user->notify(new PqrUpdatedNotification($pqr));
            }
        }

    }

    public function created(Pqr $pqr)
    {

        $user_id = $this->getUserId($pqr);

        $this->createPqrUser($pqr, $user_id);

        $pqr->messages()->create([
            "sender_type" => $this->getSenderType($pqr),
            "sent_by" => $this->getSenderId($pqr),
            "message" => $pqr->description
        ]);
    }

    private function getUserId(Pqr $pqr)
    {
        if (!$client = $pqr->client) {
            return;
        }

        if ($pqr->client->technician->first()) {
            return $pqr->client->technician->first()->technician->user_id;
        }
        return ($pqr->client->networkOperator ? $pqr->client->networkOperator->user_id : null);
    }

    private function createPqrUser($pqr, $user_id)
    {
        if ($user_id) {
            $pqr->pqrUsers()->create([
                "user_id" => $user_id,
                "status" => PqrUser::STATUS_ENABLED
            ]);
        }

        if ($user = Auth::user()) {
            $pqr->pqrUsers()->create([
                "user_id" => $user->id,
                "status" => PqrUser::STATUS_ENABLED
            ]);
        }
        if ($pqr->type == Pqr::PQR_TYPE_BILLING) {
            if ($pqr->client && $sellers = $pqr->client->clientSellers) {
                foreach ($sellers as $seller) {
                    $pqr->pqrUsers()->create([
                        "user_id" => $seller->seller->user_id,
                        "status" => PqrUser::STATUS_ENABLED
                    ]);
                }
            }
        }
        if ($pqr->client && $supervisors = $pqr->client->supervisors) {
            foreach ($supervisors as $supervisor) {
                $pqr->pqrUsers()->create([
                    "user_id" => $supervisor->user_id,
                    "status" => PqrUser::STATUS_ENABLED
                ]);
            }
        }
    }

    private function getSenderType($pqr)
    {

        if ($pqr->supervisor_id) {
            return PqrMessage::SENDER_TYPE_SUPERVISOR;
        }
        if ($pqr->network_operator_id) {
            return PqrMessage::SENDER_TYPE_NETWORK_OPERATOR;
        }
        return PqrMessage::SENDER_TYPE_CLIENT;
    }

    private function getSenderId($pqr)
    {
        if ($pqr->supervisor_id) {
            return $pqr->supervisor_id;
        }
        if ($pqr->network_operator_id) {
            return $pqr->network_operator_id;
        }
        return $pqr->client_id;
    }

    private function getSupport()
    {
        if (!$support = Support::wherePqrAvailable(true)->inRandomOrder()->first()) {
            return null;
        }

        return $support->id;
    }

    private function getSupportUser()
    {
        if (!$support = Support::wherePqrAvailable(true)->inRandomOrder()->first()) {
            return null;
        }

        return $support->user_id;
    }
}
