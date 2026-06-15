<?php

namespace App\Listeners;

use App\Jobs\MoyenneEditJob;
use App\Events\MoyenneEditEvent;
use App\Services\GestionNoteService;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoyenneEditListener implements ShouldQueue
{
    public function handle(MoyenneEditEvent $event): void
    {
        $service = app(GestionNoteService::class);
        $table = [];
        $stds = $event->students;
        $moyens = $event->moyens;
        foreach($stds as $i => $item ) {
            list($id, $genre) = explode('_', $item);
            $table[] = [
                'id' => $id,
                'genre' => $genre,
                'moyen' => $this->format($moyens[$i])
            ];
        }

        list($classe, $matter, $cutting) = explode('_', $event->str);

        // Déclenchement de job
        MoyenneEditJob::dispatch($table, $matter, $cutting, $service->classe($classe));
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
