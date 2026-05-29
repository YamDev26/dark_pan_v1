<?php

namespace App\Listeners;

use App\Services\MoyenneService;
use App\Jobs\MoyenneTrimestreJob;
use App\Events\MoyenneBilanMatterEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoyenneBilanMatterListener implements ShouldQueue
{
    
    public function handle(MoyenneBilanMatterEvent $event): void
    {
        $dts = ClassementStudent($event->data);
        $service = app(MoyenneService::class);

        foreach($dts as $item) {
            $service->saveMoyenneBilanMatter(
                $item['id'],
                $item['moyen'],
                $item['rang'],
                $item['value'],
                $event->bilan,
                $event->cutting
            );
        }

        // Déclenchement de job 
        MoyenneTrimestreJob::dispatch($event->data, $event->cutting);
    }
}
