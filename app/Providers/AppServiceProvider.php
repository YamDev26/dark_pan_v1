<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        Event::listen(
            App\Events\MoyenneMatterStoreEvent::class,
            App\Listeners\MoyenneMatterStoreListener::class,

            App\Events\MoyenneBilanMatterEvent::class,
            App\Listeners\MoyenneBilanMatterListener::class,

            App\Events\MoyenneTrimestreEvent::class,
            App\Listeners\MoyenneTrimestreListener::class,
        );
    }
}
