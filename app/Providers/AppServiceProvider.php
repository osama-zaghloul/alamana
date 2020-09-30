<?php

namespace App\Providers;

use App\setting;
use App\category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */  
    public function boot()
    {
            View::composer('front.include.homemaster', function ($view) {
            $view->with('appsetting'  , setting::first());
            $view->with('allcats'     , category::get());
        });
    }
}
