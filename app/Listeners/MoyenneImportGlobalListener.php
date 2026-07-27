<?php

namespace App\Listeners;

use App\Jobs\MoyenneEditJob;
use App\Jobs\SubMatterImportJob;
use App\Events\MoyenneImportGlobalEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoyenneImportGlobalListener implements ShouldQueue
{
    private const SUB_MATTERS = ['CF', 'OG', 'EO'];

    public function handle(MoyenneImportGlobalEvent $event): void
    {
        $data = $event->data;

        foreach($data as $matiere => $valeur) {

            list($id, $libelle) = explode('_', $matiere, 2);

            $job = in_array($libelle, self::SUB_MATTERS, true)
            ? new SubMatterImportJob(
                $valeur, $id, $event->cutting, $event->classe, $event->user
            )
            : new MoyenneEditJob(
                $valeur, $id, $event->cutting, $event->classe, $event->user
            );

            dispatch($job);
        }
    }
}
