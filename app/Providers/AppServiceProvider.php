<?php

namespace App\Providers;

use App;
use Auth;
use Illuminate\Support\ServiceProvider;
use Validator;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $view->with('globalUser', Auth::user());
        });

        Validator::extend('amount', function($attribute, $value, $parameters, $validator) {
            $amountFormatter = new \NumberFormatter(App::getLocale(), \NumberFormatter::DECIMAL);
            return ! $amountFormatter->parse($value) === false;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
