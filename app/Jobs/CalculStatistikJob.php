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
        $resutl = $service->tauxReussite(
            $this->cutting, $class->level_id
        );

        // Enregistrment
        $service->statistikSave(
            $class->level_id, $this->cutting, $resutl->effectif, $resutl->garcons, $resutl->filles, $resutl->admis,
            $resutl->classes, $resutl->non_classes, $resutl->admis_garcons, $resutl->admis_filles,
            $this->format($resutl->taux_reussite), $this->format($resutl->taux_garcons), $this->format($resutl->taux_filles)
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
