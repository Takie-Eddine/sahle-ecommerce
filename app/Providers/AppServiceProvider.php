<?php

namespace App\Providers;

use App\Basket\Basket;
use App\Support\Storage\SessionStorage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    public Basket $basket;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191) ;

        $this->app->bind(StorageInterface::class, function ($app) {
            return new SessionStorage('basket');

        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


    }
}
