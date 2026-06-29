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

        $this->ensurePopupSettings();
    }

    private function ensurePopupSettings(): void
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) return;
            if (\Illuminate\Support\Facades\DB::table('settings')->where('key', 'popup.enabled')->exists()) return;

            \Illuminate\Support\Facades\DB::table('settings')->insert([
                ['key' => 'popup.enabled',    'display_name' => 'Activer le popup',          'value' => '0', 'details' => '', 'type' => 'checkbox', 'order' => 1, 'group' => 'Popup'],
                ['key' => 'popup.image',      'display_name' => 'Image du popup (800×600)',   'value' => '', 'details' => '',  'type' => 'image',    'order' => 2, 'group' => 'Popup'],
                ['key' => 'popup.link',       'display_name' => 'Lien au clic (optionnel)',   'value' => '', 'details' => '',  'type' => 'text',     'order' => 3, 'group' => 'Popup'],
                ['key' => 'popup.frequency',  'display_name' => 'Fréquence (once / always)',  'value' => 'once', 'details' => '', 'type' => 'text',  'order' => 4, 'group' => 'Popup'],
            ]);
        } catch (\Exception $e) {
            // Silently fail if settings table not ready
        }
    }
}
