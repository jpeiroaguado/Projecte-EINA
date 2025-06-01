<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public const HOME = '/redirect-segons-rol';

    public function register(): void
{
    $this->app->register(\App\Providers\BroadcastServiceProvider::class);

}



    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            //
    }
}
