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

    private $data, $cutting;
    public function __construct($data, $cutting)
    {
        $this->data = $data;
        $this->cutting = $cutting;
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
            $table, 
            $this->cutting
        );
    }
}
