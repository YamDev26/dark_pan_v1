<?php

namespace App\Listeners;

use App\Jobs\MoyenneEditJob;
use App\Services\GestionNoteService;
use App\Events\MoyenneEditDrivingEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoyenneEditDrivingListener implements ShouldQueue
{
    
    public function handle(MoyenneEditDrivingEvent $event): void
    {
        list($classe, $matter, $cutting) = explode('_', $event->str);
        $service = app(GestionNoteService::class);
        $stds = $event->students;
        $moyens = $event->moyens;
        $table = [];
        foreach($stds as $i => $item ) {
            list($id, $genre) = explode('_', $item);
            $table[] = [
                'id' => $id,
                'genre' => $genre,
                'moyen' => $this->format($moyens[$i])
            ];

            $service->addAbsence(
                $id, $cutting, $event->absJust[$i], $event->absNons[$i]
            );
        }

        // Déclenchement de job
        MoyenneEditJob::dispatch(
            $table, $matter, $cutting, $service->classe($classe), $event->user
        );
    }


    private function format($moyenne) {
        if (blank($moyenne)) {
            return 'nc';
        }
        $value = str_replace([' ', ','], ['', '.'], trim((string) $moyenne));
        if (!is_numeric($value)) {
            return 'nc';
        }
        return (float) $value === 20.0
        ? '20'
        : sprintf('%05.2f', $value);
    }
}
