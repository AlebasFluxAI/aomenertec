<?php

namespace App\Providers;

use App\Models\V1\Admin;
use App\Models\V1\BillingInformation;
use App\Models\V1\ClientAddress;
use App\Models\V1\ClientConfiguration;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentType;
use App\Models\V1\Image;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\NetworkOperator;
use App\Models\V1\PqrMessage;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Observers\BillingInformationObserver;
use App\Observers\ClientAddressObserver;
use App\Observers\ClientConfiguration\ClientConfigurationObserver;
use App\Observers\Equipment\EquipmentObserver;
use App\Observers\HereMapObserver;
use App\Observers\MicrocontrollerData\MicrocontrollerDataObserver;
use App\Observers\Pqr\PqrMessageObserver;
use App\Observers\Image\ImageObserver;
use App\Observers\User\Admin\UserAdminObserver;
use App\Observers\User\NetworkOperator\UserNetworkOperatorObserver;
use App\Observers\User\Seller\UserSellerObserver;
use App\Observers\User\SuperAdmin\UserSuperAdminObserver;
use App\Observers\User\Supervisor\UserSupervisorObserver;
use App\Observers\User\Support\UserSupportObserver;
use App\Observers\User\Technician\UserTechnicianObserver;
use App\Observers\User\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        PqrMessage::observe(PqrMessageObserver::class);
        Image::observe(ImageObserver::class);
        MicrocontrollerData::observe(MicrocontrollerDataObserver::class);
        Admin::observe(UserAdminObserver::class);
        NetworkOperator::observe(UserNetworkOperatorObserver::class);
        Seller::observe(UserSellerObserver::class);
        SuperAdmin::observe(UserSuperAdminObserver::class);
        Supervisor::observe(UserSupervisorObserver::class);
        Technician::observe(UserTechnicianObserver::class);
        Support::observe(UserSupportObserver::class);
        User::observe(UserObserver::class);
        ClientConfiguration::observe(ClientConfigurationObserver::class);
        Equipment::observe(EquipmentObserver::class);
        ClientAddress::observe(ClientAddressObserver::class);
        ClientAddress::observe(HereMapObserver::class);
        BillingInformation::observe(BillingInformationObserver::class);
    }
}

