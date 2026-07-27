<?php

namespace App\Jobs;

use App\Services\StatistikService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculStatistikJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const SYMBOL_1 = '<'; private const SYMBOL_2 = '>=';

    protected $classe, $cutting, $user;

    public function __construct($classe, $cutting, $user)
    {
        $this->classe = $classe;
        $this->cutting = $cutting;
        $this->user = $user;
    }

    
    public function handle(): void
    {
        $service = app(StatistikService::class);

        $class = $service->getClasse($this->classe);

        
        // Enregistrment de resultat par niveau
        $resutl = $service->tauxReussite(
            $this->cutting, $class->level_id, $this->user->school_id
        );

        $service->statistikSave(
            $class->level_id, $this->cutting, $this->user->school_id, [$resutl->effectif, $resutl->garcons, $resutl->filles,
            $resutl->admis, $resutl->admis_garcons, $resutl->admis_filles, $this->format($resutl->taux_reussite),
            $this->format($resutl->taux_garcons), $this->format($resutl->taux_filles), $resutl->classes, $resutl->non_classes]
        );

        // Enregistrment de resultat par niveau et serie
        if($class->serie_id) {
            $resultSerie = $service->tauxResultatSerie(
                $this->cutting, $class->level_id, $this->serie($class->serie_id), $this->user->school_id
            );

            $cycle2 = $service->getResultatCycle($this->cutting, self::SYMBOL_2, $this->user->school_id);
            $type = 'cycle2';

            $service->saveResultatSerie(
                $class->level_id, $class->serie_id, $this->cutting, $this->user->school_id, [$resultSerie->effectif, $resultSerie->garcons,
                $resultSerie->filles, $resultSerie->admis, $resultSerie->admis_garcons, $resultSerie->admis_filles, $this->format($resultSerie->taux_reussite),
                $this->format($resultSerie->taux_garcons), $this->format($resultSerie->taux_filles), $resultSerie->classes, $resultSerie->non_classes]
            );

            $service->SaveResultatGlobal(
                $this->cutting, $type, $this->user->school_id, [$cycle2->effectif, $cycle2->garcons, $cycle2->filles,
                $cycle2->admis, $cycle2->admis_garcons, $cycle2->admis_filles, $this->format($cycle2->taux_reussite), 
                $this->format($cycle2->taux_garcons), $this->format($cycle2->taux_filles), $cycle2->classes, $cycle2->non_classes]
            );
        }
        else { // Enregistrement Resultat Cycle 1
            $cycle1 = $service->getResultatCycle($this->cutting, self::SYMBOL_1, $this->user->school_id);
            $type = 'cycle1';
            $service->SaveResultatGlobal(
                $this->cutting, $type, $this->user->school_id, [$cycle1->effectif, $cycle1->garcons, $cycle1->filles, 
                $cycle1->admis, $cycle1->admis_garcons, $cycle1->admis_filles, $this->format($cycle1->taux_reussite), 
                $this->format($cycle1->taux_garcons), $this->format($cycle1->taux_filles), $cycle1->classes, $cycle1->non_classes]
            );
        }

        // Enregistrment de resultat scolaire
        $global = $service->getResultatScolaire($this->cutting, $this->user->school_id);
        $type = 'total';
        $service->SaveResultatGlobal(
            $this->cutting, $type, $this->user->school_id, [$global->effectif, $global->garcons, $global->filles, 
            $global->admis, $global->admis_garcons, $global->admis_filles, $this->format($global->taux_reussite), 
            $this->format($global->taux_garcons), $this->format($global->taux_filles), $global->classes, $global->non_classes]
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


    private function serie($id) {
        return in_array($id, [1, 2, 3]) ? 1 : $id;
    }
}
