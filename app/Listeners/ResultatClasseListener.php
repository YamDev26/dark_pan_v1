<?php

namespace App\Listeners;

use App\Jobs\CalculStatistikJob;
use App\Services\ResultatService;
use App\Events\ResultatClasseEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResultatClasseListener implements ShouldQueue
{
    
    public function handle(ResultatClasseEvent $event): void
    {
        $service = app(ResultatService::class);

        $resultClasse = $service->statistiquesClasse(
            $event->classe, $event->cutting
        );

        $resultGenre = $service->statistiquesClasseGenre(
            $event->classe, $event->cutting
        );

        $byGenre = $resultGenre->keyBy('genre');
        if($resultClasse && $resultGenre) {

            $service->resultatClasseSave(
                $event->classe, $event->cutting, $this->format($resultClasse->moyenne), $resultClasse->effectif, 
                $this->format($resultClasse->taux), $this->format($byGenre->get('F')->taux ?? 0), 
                $this->format($byGenre->get('M')->taux ?? 0), $resultClasse->moins_de_850
            );

            // Enregistrement Des Tranches Des Moyennes 
            $service->TrancheMoyenneSavec($event->classe, $event->cutting);
        }

        // Declessement de Job
        CalculStatistikJob::dispatch(
            $event->classe, $event->cutting, $event->user
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
        return (float) $value === 100.0
        ? '100'
        : sprintf('%05.2f', $value);
    }
}
