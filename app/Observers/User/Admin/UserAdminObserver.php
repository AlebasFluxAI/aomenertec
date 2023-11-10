<?php

namespace App\Observers\User\Admin;

use App\Models\V1\Admin;
use App\Models\V1\TabPermission;
use Illuminate\Support\Facades\Http;

class UserAdminObserver
{
    /**
     * Handle the SuperAdmin "created" event.
     *
     * @param Admin $admin
     * @return void
     */
    public function creating(Admin $admin)
    {
        $this->setHereMapJson($admin);
        $this->setInformation($admin);
        $user = $admin->user;
        if (!$user) {
            return;
        }

        $admin->email = $user->email;
        $admin->name = $user->name;
        $admin->last_name = $user->last_name;
        $admin->phone = $user->phone;
        $admin->identification = $user->identification;
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

    public function setInformation($model)
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

    public function created(Admin $admin)
    {
        $tabPermissionId = TabPermission::wherePermission(TabPermission::CLIENT_BILLING_CONFIG)->first()->id;

        $admin->tabPermissions()->create([
            "tab_permission_id" => $tabPermissionId
        ]);

    }

    /**
     * Handle the SuperAdmin "updated" event.
     *
     * @param Admin $admin
     * @return void
     */
    public function updated(Admin $admin)
    {
        $this->setHereMapJson($admin);
        $this->setInformation($admin);
        $user = $admin->user;
        if (!$user) {
            return;
        }

        $user->update([
            "name" => $admin->name,
            "last_name" => $admin->last_name,
            "email" => $admin->email,
            "phone" => $admin->phone,
            "identification" => $admin->identification,
        ]);
    }

    /**
     * Handle the SuperAdmin "restored" event.
     *
     * @param Admin $admin
     * @return void
     */
    public function restored(Admin $admin)
    {
        //
    }

    /**
     * Handle the SuperAdmin "force deleted" event.
     *
     * @param Admin $admin
     * @return void
     */
    public function forceDeleted(Admin $admin)
    {
        //
    }
}
