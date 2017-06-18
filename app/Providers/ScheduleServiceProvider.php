<?php

namespace App\Providers;

use App\Services\ScheduleService;
use Illuminate\Support\ServiceProvider;

class ScheduleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ScheduleService::class, function () {
            return new ScheduleService();
        });
    }
}
