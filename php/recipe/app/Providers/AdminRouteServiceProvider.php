<?php

namespace App\Providers;

use App\Routes\AdminRouteMethods;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AdminRouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Route::mixin(new AdminRouteMethods);
    }
}
