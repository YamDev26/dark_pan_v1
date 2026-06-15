<?php

namespace App\Jobs;

use App\Services\MoyenneService;
use App\Events\MoyenneBilanMatterEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoyenneBilanMatterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data, $matter, $cutting, $classe;
    public function __construct($data, $matter, $cutting, $classe)
    {
        $this->data = $data;
        $this->matter = $matter;
        $this->cutting = $cutting;
        $this->classe = $classe;
    }

    
    public function handle(): void
    {
        $service = app(MoyenneService::class);
        $bilan = $service->getBilanMatter($this->matter);

        $table = [];
        foreach($this->data as $item) {
            $total = $service->sumMoyenneMatterBilan($item['id'], $this->cutting, $bilan);

            $table[] = [
                'id' => $item['id'],
                'genre' => $item['genre'],
                'moyen' => moyenneCalcul($total->total, $total->value),
                'value' => $total->value
            ];
        }

        // Déclenchement d'Evenement
        MoyenneBilanMatterEvent::dispatch(
            $table, 
            $bilan, 
            $this->cutting,
            $this->classe
        );
    }
}
