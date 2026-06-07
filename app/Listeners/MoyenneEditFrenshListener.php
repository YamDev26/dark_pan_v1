<?php

namespace App\Listeners;

use App\Events\MoyenneEditFrenshEvent;
use App\Jobs\MoyenneImportFrenshJob;
use App\Services\GestionNoteService;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoyenneEditFrenshListener implements ShouldQueue
{
    
    public function handle(MoyenneEditFrenshEvent $event): void
    {
        $table1 = []; $table2 = []; $table3 = [];
        $stds = $event->students;
        $moyen1 = $event->moyen1;
        $moyen2 = $event->moyen2;
        $moyen3 = $event->moyen3;

        foreach($stds as $i => $item) {
            list($id, $genre) = explode('_', $item);
            $base = [
                'id'    => $id,
                'genre' => $genre,
            ];

            $table1[] = $base + [
                'moyen' => $this->format($moyen1[$i]),
            ];

            $table2[] = $base + [
                'moyen' => $this->format($moyen2[$i]),
            ];

            $table3[] = $base + [
                'moyen' => $this->format($moyen3[$i]),
            ];
        }

        $service = app(GestionNoteService::class);
        list($classe, $matter, $cutting) = explode('_', $event->str);

        MoyenneImportFrenshJob::dispatch(
            [$table1, $table2, $table3],
            $matter, 
            $cutting,
            $service->classe($classe)
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
