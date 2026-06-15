<?php

namespace App\Jobs;

use App\Services\ResultatService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TauxReussiteMatterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $matter, $cutting, $classe;
    public function __construct($matter, $cutting, $classe)
    {
        $this->matter = $matter;
        $this->cutting = $cutting;
        $this->classe = $classe;
    }

    
    public function handle(): void
    {
        $service = app(ResultatService::class);

        $result = $service->tauxReussiteMatter(
            $this->matter,
            $this->classe,
            $this->cutting
        );

        if($result->resultat) {
            $service->tauxReussiteMatterSave(
                $this->matter,
                $this->cutting,
                $this->classe,
                $this->format($result->resultat)
            );
        }
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
