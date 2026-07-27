<?php

namespace App\Listeners;

use App\Services\MoyenneService;
use App\Events\ResultatClasseEvent;
use App\Events\MoyenneTrimestreEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoyenneTrimestreListener implements ShouldQueue
{

    public function handle(MoyenneTrimestreEvent $event): void
    {
        $dts = ClassementStudent($event->data);
        $service = app(MoyenneService::class);

        foreach($dts as $item) {
            $service->saveMoyenneTrimestre(
                $item['id'],
                $item['moyen'],
                $item['rang'],
                $item['value'],
                $event->cutting
            );
        }

        // Déclenchement d'Evenement
        ResultatClasseEvent::dispatch(
            $event->classe, $event->cutting, $event->user
        );
    }
}
