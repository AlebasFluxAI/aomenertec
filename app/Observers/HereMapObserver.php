<?php

namespace App\Observers;

use App\Models\Core\City;
use App\Models\Core\Department;
use Throwable;

class HereMapObserver
{
    public function creating($model)
    {
        $this->setInformation($model);
    }

    public function setInformation(&$model)
    {
        if (!$model->here_maps) {
            return;
        }

        $map = json_decode($model->here_maps ?? '{}');

        if (!$map) {
            return;
        }

        try {
            if (!array_key_exists(0, $map->items)) {
                return;
            }
            $map = $map->items[0];
            $hereAddress = $map->address;
            $model->postal_code = $hereAddress->postalCode ?? null;

            $hereMap = json_decode($model->here_maps, true);

            if (array_key_exists('items', $hereMap)) {
                if (count($hereMap['items']) > 0) {
                    if (array_key_exists('address', $hereMap['items'][0])) {
                        $model->address = array_key_exists('label', $hereMap['items'][0]['address']) ? $hereMap['items'][0]['address']['label'] : "";
                        $model->country = array_key_exists('countryName', $hereMap['items'][0]['address']) ? $hereMap['items'][0]['address']['countryName'] : "";
                        $model->state = array_key_exists('county', $hereMap['items'][0]['address']) ? $hereMap['items'][0]['address']['county'] : "";
                        $model->city = array_key_exists('city', $hereMap['items'][0]['address']) ? $hereMap['items'][0]['address']['city'] : "";
                        $model->postal_code = array_key_exists('postalCode', $hereMap['items'][0]['address']) ? $hereMap['items'][0]['address']['postalCode'] : "";
                    }
                }
            }
        } catch (Throwable $e) {
        }
    }

    public function updating($model)
    {
        $this->setInformation($model);
    }
}
