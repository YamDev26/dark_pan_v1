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

    protected $classe, $cutting;
    public function __construct($classe, $cutting)
    {
        $this->classe = $classe;
        $this->cutting = $cutting;
    }

    
    public function handle(): void
    {
        $service = app(StatistikService::class);

        $class = $service->getClasse($this->classe);

        
        // Enregistrment de resultat par niveau
        $resutl = $service->tauxReussite($this->cutting, $class->level_id)
        ;
        $service->statistikSave(
            $class->level_id, $this->cutting, $resutl->effectif, $resutl->garcons, $resutl->filles, $resutl->admis,
            $resutl->classes, $resutl->non_classes, $resutl->admis_garcons, $resutl->admis_filles,
            $this->format($resutl->taux_reussite), $this->format($resutl->taux_garcons), $this->format($resutl->taux_filles)
        );

        // Enregistrment de resultat par niveau et serie
        if($class->serie_id) {
            $resultSerie = $service->tauxResultatSerie(
                $this->cutting, $class->level_id, $this->serie($class->serie_id)
            );

            $service->saveResultatSerie(
                $class->level_id, $class->serie_id, $this->cutting, $resultSerie->effectif, $resultSerie->garcons, $resultSerie->filles,
                $resultSerie->admis, $resultSerie->admis_garcons, $resultSerie->admis_filles, $this->format($resultSerie->taux_reussite),
                $this->format($resultSerie->taux_garcons), $this->format($resultSerie->taux_filles), $resultSerie->classes, $resultSerie->non_classes
            );
        }

        // Enregistrment de resultat scolaire
        $global = $service->getResultatScolaire($this->cutting);

        $service->SaveResultatGlobal(
            $this->cutting, $global->effectif, $global->garcons, $global->filles, $global->admis, 
            $global->admis_garcons, $global->admis_filles, $this->format($global->taux_reussite), 
            $this->format($global->taux_garcons), $this->format($global->taux_filles), $global->classes, $global->non_classes
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
