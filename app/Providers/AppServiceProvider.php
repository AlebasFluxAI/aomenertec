<?php

namespace App\Providers;

use App\Models\Traits\AuditableTrait;
use App\Models\V1\Admin;
use App\Models\V1\BillingInformation;
use App\Models\V1\ClientAddress;
use App\Models\V1\ClientAlertConfiguration;
use App\Models\V1\ClientRecharge;
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
use App\Observers\HereMapObserver;
use App\Observers\MicrocontrollerData\MicrocontrollerDataObserver;
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
use App\Observers\V1\Change\ChangeObserver;
use App\Observers\V1\Pqr\PqrLogObserver;
use App\Observers\V1\PqrUser\PqrUserObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\PersonalAccessToken;


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

        Event::listen(['eloquent.deleting:*', 'eloquent.updating:*', 'eloquent.creating:*'], function ($model) {
            $model = trim(explode(':', $model)[1]);
            if (is_subclass_of($model, Model::class) and
                array_key_exists(AuditableTrait::class, class_uses_recursive($model))) {
                $model::observe(ChangeObserver::class);
            }
        });

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
        Admin::observe(UserAdminObserver::class);

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


        PqrUser::observe(PqrUserObserver::class);

        //ACTION AUDIT
        PqrLog::observe(ActionByObserve::class);
        ClientRecharge::observe(ActionByObserve::class);
        HistoricalClientEquipment::observe(ActionByObserve::class);
    }
}
