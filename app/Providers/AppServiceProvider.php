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
            App\Events\MoyenneBilanMatterEvent::class,
            App\Listeners\MoyenneBilanMatterListener::class,

            App\Events\MoyenneTrimestreEvent::class,
            App\Listeners\MoyenneTrimestreListener::class,

            App\Events\EvaluatNotEvant::class,
            App\Listeners\EvaluatNotListener::class,

            App\Events\FrenshMoyenneEvent::class,
            App\Listeners\FrenshMoyenneListener::class,

            App\Events\MoyenneEditEvent::class,
            App\Listeners\MoyenneEditListener::class,

            App\Events\MoyenneEditFrenshEvent::class,
            App\Listeners\MoyenneEditFrenshListener::class,

            App\Events\NonClasseStudentEvent::class,
            App\Listeners\NonClasseStudentListener::class,

            App\Events\MoyenneImportGlobalEvent::class,
            App\Listeners\MoyenneImportGlobalListener::class,

            App\Events\ResultatClasseEvent::class,
            App\Listeners\ResultatClasseListener::class,
        );
    }
}
