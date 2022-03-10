<?php

namespace App\Providers;

use App\Models\V1\Image;
use App\Models\V1\MicrocontrollerData;
use App\Models\V1\PqrMessage;
use App\Observers\MicrocontrollerData\MicrocontrollerDataObserver;
use App\Observers\Pqr\PqrMessageObserver;
use App\Observers\Image\ImageObserver;
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
    }
}
