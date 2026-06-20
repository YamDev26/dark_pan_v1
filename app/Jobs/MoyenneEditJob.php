<?php

namespace App\Jobs;

use App\Services\MoyenneService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoyenneEditJob implements ShouldQueue
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
        $dts = ClassementStudent($this->data);
        $service = app(MoyenneService::class);
        
        foreach($dts as $item) {
            $service->saveMoyenneMatter(
                $item['id'], $item['moyen'], $this->matter, $this->cutting, $item['rang']
            );
        }

        // Déclenchement de job
        Bus::chain([
            new MoyenneBilanMatterJob($this->data, $this->matter, $this->cutting, $this->classe->id),
            new TauxReussiteMatterJob($this->matter, $this->cutting, $this->classe->id),
        ])->dispatch();
    }
}
