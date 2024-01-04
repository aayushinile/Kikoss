<?php

namespace App\Providers;

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
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if(request()->getHttpHost() == '127.0.0.1:8000'){
            
        }else{
            if($this->app->environment('production')) {
                \URL::forceScheme('https');
            }
    
            $this->app['request']->server->set('HTTPS','on');
        }
    }
}