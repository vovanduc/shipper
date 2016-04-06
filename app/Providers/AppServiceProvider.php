<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Http\Repositories\User\IUserRepository', 'App\Http\Repositories\User\UserRepository');
        $this->app->bind('App\Http\Repositories\Customer\ICustomerRepository', 'App\Http\Repositories\Customer\CustomerRepository');
        $this->app->bind('App\Http\Repositories\Shipper\IShipperRepository', 'App\Http\Repositories\Shipper\ShipperRepository');
        $this->app->bind('App\Http\Repositories\Package\IPackageRepository', 'App\Http\Repositories\Package\PackageRepository');
        $this->app->bind('App\Http\Repositories\Location\ILocationRepository', 'App\Http\Repositories\Location\LocationRepository');
        $this->app->bind('App\Http\Repositories\Shipment\IShipmentRepository', 'App\Http\Repositories\Shipment\ShipmentRepository');
    }
}
