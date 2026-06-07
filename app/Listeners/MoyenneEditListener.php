<?php

namespace App\Listeners;

use App\Events\MoyenneEditEvent;
use App\Jobs\MoyenneEditJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoyenneEditListener implements ShouldQueue
{
    public function handle(MoyenneEditEvent $event): void
    {
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
        MoyenneEditJob::dispatch($table, $matter, $cutting, $classe);
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
