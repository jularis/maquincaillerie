<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if (request()->segment(1) === 'admin') {
            Paginator::useBootstrapThree();
        } else {
            Paginator::defaultView('pagination::tailwind');
            Paginator::defaultSimpleView('pagination::simple-tailwind');
        }
    }
}
