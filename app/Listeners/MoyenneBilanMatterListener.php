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
            if (blank($item['moyen']) || $item['moyen'] === 'nc') {
                continue;
            }
            $service->saveMoyenneBilanMatter(
                $item['id'],
                $item['moyen'],
                $item['value'] ?? 'nc',
                $event->bilan,
                $event->cutting,
                $item['rang']
            );
        }

        // Déclenchement de job 
        MoyenneTrimestreJob::dispatch($event->data, $event->cutting, $event->classe, $event->user);
    }
}
