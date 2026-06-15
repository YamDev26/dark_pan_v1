<?php

namespace App\Listeners;

use App\Services\MoyenneService;
use App\Events\FrenshMoyenneEvent;
use App\Jobs\MoyenneEditJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class FrenshMoyenneListener implements ShouldQueue
{
    
    public function handle(FrenshMoyenneEvent $event): void
    {
        $service = app(MoyenneService::class);

        $stds = $service->getStudent($event->classe);
        $classe = $service->getClasse($event->classe);

        $table = [];
        foreach($stds as $item) {
            $dta = $service->getMoyenneSubMatter($item->id, $event->cutting);
            $table[] = [
                'id' => $item->id,
                'genre' => $item->genre,
                'moyen' => $this->moyenne($dta),
            ];
        }

        // Déclenchement de job
        MoyenneEditJob::dispatch(
            $table, $event->matter, $event->cutting, $classe
        );
        
    }


    private function moyenne($data) {
        $total = 0;
        $coeff = 0;

        foreach ($data as $item) {
            if ($item->moyenne !== 'nc') {
                $total += (float) $item->moyenne;
                $coeff += (float) $item->values;
            }
        }

        if ($coeff <= 0) {
            return $total > 0 ? '00' : 'nc';
        }

        return sprintf('%05.2f', $total / $coeff);
    }
}
