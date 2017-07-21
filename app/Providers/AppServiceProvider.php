<?php

namespace App\Providers;

use Illuminate\Http\Request;
use App\Lib\RandomPasswordGenerator;
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
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RandomPasswordGenerator::class, function () {
            return new RandomPasswordGenerator();
        });

        Request::macro('checkForCss', function ($path, $classString = '') {
            if ($this->url() === url($path)) {
                return $classString;
            }

            return '';
        });
    }
}
