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
                $clientConfiguration->client->networkOperator->addTabPermissionPlusConditional(
                    TabPermission::wherePermission(TabPermission::CLIENT_MONITORING_REAL_TIME)->first()->id
                    , $clientConfiguration->client);
                $clientConfiguration->client->technician()->first()->technician->addTabPermissionPlusConditional(
                    TabPermission::wherePermission(TabPermission::CLIENT_MONITORING_REAL_TIME)->first()->id
                    , $clientConfiguration->client);
                foreach ($clientConfiguration->client->supervisors as $supervisor) {
                    $supervisor->addTabPermissionPlusConditional(
                        TabPermission::wherePermission(TabPermission::CLIENT_MONITORING_REAL_TIME)->first()->id
                        , $clientConfiguration->client);
                }
            } else {
                $clientConfiguration->client->admin->removeTabPermissionPlusConditional(
                    TabPermission::wherePermission(TabPermission::CLIENT_MONITORING_REAL_TIME)->first()->id
                    , $clientConfiguration->client);
                $clientConfiguration->client->networkOperator->removeTabPermissionPlusConditional(
                    TabPermission::wherePermission(TabPermission::CLIENT_MONITORING_REAL_TIME)->first()->id
                    , $clientConfiguration->client);
                $clientConfiguration->client->technician()->first()->technician->removeTabPermissionPlusConditional(
                    TabPermission::wherePermission(TabPermission::CLIENT_MONITORING_REAL_TIME)->first()->id
                    , $clientConfiguration->client);
                foreach ($clientConfiguration->client->supervisors as $supervisor) {
                    $supervisor->removeTabPermissionPlusConditional(
                        TabPermission::wherePermission(TabPermission::CLIENT_MONITORING_REAL_TIME)->first()->id
                        , $clientConfiguration->client);
                }

            }


        }

    }
}
