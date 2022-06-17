<?php

namespace App\Observers;

use App\Models\V1\ClientAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class AddressObserver
{
    public function creating(Model $model)
    {
        $this->setHereMapJson($model);
    }

    public function setHereMapJson($model)
    {
        if (!$model->latitude or !$model->longitude) {
            return;
        }

        $latlng = "{$model->latitude},{$model->longitude}";
        $response = Http::get('https://revgeocode.search.hereapi.com/v1/revgeocode', [
            'at' => $latlng,
            'apiKey' => config("here.apiKey"),
        ]);

        if (200 == $response->status()) {
            $body = $response->json();

            if (array_key_exists('items', $body)) {
                $model->here_maps = json_encode($body);
            }
        }
    }

    public function updating(Model $model)
    {
        $this->setHereMapJson($model);
    }
}
