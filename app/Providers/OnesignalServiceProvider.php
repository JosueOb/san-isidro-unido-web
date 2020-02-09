<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class OnesignalServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        require_once app_path() . "/Helpers/OnesignalNotification.php";

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
