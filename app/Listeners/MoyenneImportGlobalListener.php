<?php

namespace App\Listeners;

use App\Jobs\MoyenneEditJob;
use App\Jobs\SubMatterImportJob;
use App\Events\MoyenneImportGlobalEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoyenneImportGlobalListener implements ShouldQueue
{

    public function handle(MoyenneImportGlobalEvent $event): void
    {
        $data = $event->data;

        foreach($data as $matiere => $valeur) {

            list($id, $lib) = explode('_', $matiere);

            in_array($lib, ['CF', 'OG', 'EO']) ?
            SubMatterImportJob::dispatch($valeur, $id, $event->cutting, $event->classe):
            MoyenneEditJob::dispatch($valeur, $id, $event->cutting, $event->classe);
        }
    }
}
