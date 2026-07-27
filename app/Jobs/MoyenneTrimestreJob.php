<?php

namespace App\Jobs;

use App\Services\MoyenneService;
use App\Events\MoyenneTrimestreEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoyenneTrimestreJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data, $cutting, $classe, $user;
    public function __construct($data, $cutting, $classe, $user)
    {
        $this->data = $data;
        $this->cutting = $cutting;
        $this->classe = $classe;
        $this->user = $user;
    }

    
    public function handle(): void
    {
        $service = app(MoyenneService::class);

        $table = [];
        foreach($this->data as $item) {
            $total = $service->sumMoyenneMatter($item['id'], $this->cutting);

            $table[] = [
                'id' => $item['id'],
                'genre' => $item['genre'],
                'moyen' => moyenneCalcul($total->total, $total->value),
                'value' => $total->value
            ];
        }

        // Déclenchement d'Evenement
        MoyenneTrimestreEvent::dispatch(
            $table, $this->cutting,$this->classe, $this->user
        );


        $cuts = $service->getCutting($this->cutting);
        $cuts->cutting->end == '1' ? 
        MoyenneAnnuelleJob::dispatch($this->cutting, $this->classe)
        :null;
    }
}
