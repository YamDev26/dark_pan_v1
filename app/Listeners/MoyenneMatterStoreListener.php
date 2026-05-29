<?php

namespace App\Listeners;

use App\Jobs\MoyenneImportMatterJob;
use App\Events\MoyenneMatterStoreEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoyenneMatterStoreListener implements ShouldQueue
{
    
    public function handle(MoyenneMatterStoreEvent $event): void
    {
        $table = [];
        $stds = $event->students;
        $moyens = $event->moyens;
        foreach($stds as $index => $item ) {
            list($id, $genre) = explode('_', $item);
            $table[] = [
                'id' => $id,
                'genre' => $genre,
                'moyen' => $this->format($moyens[$index])
            ];
        }

        list($matter, $cutting, $classe) = explode('_', $event->str);

        // Déclenchement de job pour le calcul de moyenne
        MoyenneImportMatterJob::dispatch($table, $matter, $cutting, $classe);
    }

    private function format($moyen) {
        if (blank($moyen)) {
            return 'nc';
        }
        $value = str_replace([' ', ','], ['', '.'], $moyen);
        if (!is_numeric($value)) {
            return 'nc';
        }
        $value = (float) $value;
        $formatted = $value > 0 ? number_format($value, 2, '.', '') : (string) $value;
        return $value < 10 ? '0' . $formatted : $formatted;
    }
}
