<?php

namespace App\Models\Traits;

use App\Models\V1\Client;

trait ClientServiceTrait
{
    public function topologies()
    {
        return [
            [
                "value" => "",
                "key" => "Topologia de red...",
            ],
            [
                "value" => "monophasic",
                "key" => "Monofasico",
            ],
            [
                "value" => "biphasic",
                "key" => "Bifasico",
            ],
            [
                "value" => "triphasic",
                "key" => "Trifasico",
            ],
        ];
    }

    public function identificationTypes()
    {
        return [
            [
                "key" => Client::IDENTIFICATION_TYPE_CC,
                "value" => Client::IDENTIFICATION_TYPE_CC,
            ],
            [
                "key" => Client::IDENTIFICATION_TYPE_CE,
                "value" => Client::IDENTIFICATION_TYPE_CE,
            ],
            [
                "key" => Client::IDENTIFICATION_TYPE_PEP,
                "value" => Client::IDENTIFICATION_TYPE_PEP,
            ],
            [
                "key" => Client::IDENTIFICATION_TYPE_PP,
                "value" => Client::IDENTIFICATION_TYPE_PP,
            ],
            [
                "key" => Client::IDENTIFICATION_TYPE_NIT,
                "value" => Client::IDENTIFICATION_TYPE_NIT,
            ],
        ];
    }

    public function getBillingInformation(Client $client)
    {
        return $client->billingInformation()->first();
    }

    public function getClientAddress(Client $client)
    {
        return $client->addresses()->first();
    }

}
