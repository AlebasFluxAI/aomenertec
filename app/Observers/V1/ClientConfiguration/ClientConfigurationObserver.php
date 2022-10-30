<?php

namespace App\Observers\V1\ClientConfiguration;

use App\Models\V1\ClientConfiguration;
use App\Models\V1\TabPermission;

class ClientConfigurationObserver
{
    public function updated(ClientConfiguration $clientConfiguration)
    {
        if ($clientConfiguration->isDirty("active_real_time")) {

            if ($clientConfiguration->active_real_time) {
                $clientConfiguration->client->admin->addTabPermissionPlusConditional(
                    TabPermission::wherePermission(TabPermission::CLIENT_MONITORING_REAL_TIME)->first()->id
                    , $clientConfiguration->client);
            } else {
                $clientConfiguration->client->admin->removeTabPermissionPlusConditional(
                    TabPermission::wherePermission(TabPermission::CLIENT_MONITORING_REAL_TIME)->first()->id
                    , $clientConfiguration->client);

            }


        }

    }
}
