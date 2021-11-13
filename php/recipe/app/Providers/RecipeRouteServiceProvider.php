<?php

namespace App\Providers;

use App\Routes\RecipeRouteMethods;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RecipeRouteServiceProvider extends ServiceProvider
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
        Route::mixin(new RecipeRouteMethods);
    }
}
