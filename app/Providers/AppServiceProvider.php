<?php

namespace App\Providers;

use App\Models\V1\Admin;
use App\Models\V1\BillingInformation;
use App\Models\V1\ClientAddress;
use App\Models\V1\ClientAlertConfiguration;
use App\Models\V1\Equipment;
use App\Models\V1\EquipmentClient;
use App\Models\V1\EquipmentType;
use App\Models\V1\HistoricalClientEquipment;
use App\Models\V1\Image;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\NetworkOperator;
use App\Models\V1\Notification;
use App\Models\V1\Pqr;
use App\Models\V1\PqrLog;
use App\Models\V1\PqrMessage;
use App\Models\V1\PqrUser;
use App\Models\V1\Seller;
use App\Models\V1\SuperAdmin;
use App\Models\V1\Supervisor;
use App\Models\V1\Support;
use App\Models\V1\Technician;
use App\Models\V1\User;
use App\Observers\ActionBy\ActionByObserve;
use App\Observers\BillingInformationObserver;
use App\Observers\AddressObserver;
use App\Observers\ClientConfiguration\ClientAlertConfigurationObserver;
use App\Observers\Equipment\EquipmentObserver;
use App\Observers\EquipmentClient\EquipmentClientObserver;
use App\Observers\HereMapObserver;
use App\Observers\HistoricalClientEquipment\HistoricalClientEquipmentObserver;
use App\Observers\MicrocontrollerData\MicrocontrollerDataObserver;
use App\Observers\NotificationObserver;
use App\Observers\Pqr\PqrMessageObserver;
use App\Observers\Image\ImageObserver;
use App\Observers\Pqr\PqrObserver;
use App\Observers\User\Admin\UserAdminObserver;
use App\Observers\User\NetworkOperator\UserNetworkOperatorObserver;
use App\Observers\User\Seller\UserSellerObserver;
use App\Observers\User\SuperAdmin\UserSuperAdminObserver;
use App\Observers\User\Supervisor\UserSupervisorObserver;
use App\Observers\User\Support\UserSupportObserver;
use App\Observers\User\Technician\UserTechnicianObserver;
use App\Observers\User\UserObserver;
use App\Observers\ClientAlert\ClientAlertObserver;
use App\Models\V1\ClientAlert;
use App\Observers\V1\Pqr\PqrLogObserver;
use App\Observers\V1\PqrUser\PqrUserObserver;
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
        //ClientAlertConfiguration::observe(ClientAlertConfigurationObserver::class);
        Equipment::observe(EquipmentObserver::class);
        ClientAlert::observe(ClientAlertObserver::class);

        ClientAddress::observe(AddressObserver::class);
        Technician::observe(AddressObserver::class);
        Seller::observe(AddressObserver::class);
        NetworkOperator::observe(AddressObserver::class);
        Support::observe(AddressObserver::class);
        Admin::observe(AddressObserver::class);
        Supervisor::observe(AddressObserver::class);


        ClientAddress::observe(HereMapObserver::class);
        Technician::observe(HereMapObserver::class);
        Seller::observe(HereMapObserver::class);
        NetworkOperator::observe(HereMapObserver::class);
        Support::observe(HereMapObserver::class);
        Admin::observe(HereMapObserver::class);
        Supervisor::observe(HereMapObserver::class);


        BillingInformation::observe(BillingInformationObserver::class);
        Pqr::observe(PqrObserver::class);
        Pqr::observe(PqrLogObserver::class);
        PqrLog::observe(ActionByObserve::class);
        HistoricalClientEquipment::observe(ActionByObserve::class);

        PqrUser::observe(PqrUserObserver::class);
        

    }
}
