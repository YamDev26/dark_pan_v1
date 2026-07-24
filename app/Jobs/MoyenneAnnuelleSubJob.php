<?php

namespace App\Jobs;

use App\Services\MoyenneAnnuelService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoyenneAnnuelleSubJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $subMatter, $matter, $cutting, $classe;

    public function __construct($subMatter, $matter, $cutting, $classe)
    {
        $this->subMatter = $subMatter;
        $this->matter = $matter;
        $this->cutting = $cutting;
        $this->classe = $classe;
    }

    
    public function handle(): void
    {
        $service = app(MoyenneAnnuelService::class);
        $service->storeMoyenneSub(
            $this->classe, $this->subMatter, $this->cutting
        );

        // Moyenne Annuelle En Français Pour Cette Classe ..............
        $service->storeMoyenneMatter(
            $this->classe, $this->matter, $this->cutting
        );

    }
}
