<?php

namespace App\Providers;

use App\Applications\Interfaces\IChartAreaServices;
use App\Applications\Services\ChartAreaServices;
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
        $this->app->bind(IChartAreaServices::class, ChartAreaServices::class);
    
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
