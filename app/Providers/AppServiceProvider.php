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
    }
}
