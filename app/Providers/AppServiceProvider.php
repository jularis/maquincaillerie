<?php

namespace App\Providers;

use App\Mail\Transport\PhpMailTransport;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Mail::extend('phpmail', fn() => new PhpMailTransport());

        if (request()->segment(1) === 'admin') {
            Paginator::useBootstrapThree();
        } else {
            Paginator::defaultView('pagination::tailwind');
            Paginator::defaultSimpleView('pagination::simple-tailwind');
        }
    }
}
