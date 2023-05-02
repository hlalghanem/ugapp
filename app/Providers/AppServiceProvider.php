<?php

namespace App\Providers;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewUserRegistered;

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
        \App\Models\User::created(function ($user) {
            Mail::to('hlal@usaimigulf.com')->send(new NewUserRegistered($user));
        });
    }
}
