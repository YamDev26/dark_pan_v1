<?php

namespace App\Jobs;

use App\Services\MoyenneService;
use App\Events\FrenshMoyenneEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoyenneSubMatterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data, $matter, $cutting, $subMatter, $classe;
    public function __construct($data, $matter, $cutting, $subMatter, $classe)
    {
        $this->data = $data;
        $this->matter = $matter;
        $this->cutting = $cutting;
        $this->subMatter = $subMatter;
        $this->classe = $classe;
    }

    
    public function handle(): void
    {
        $dts = ClassementStudent($this->data);
        $service = app(MoyenneService::class);
        foreach($dts as $item) {
            $service->moyenneSubMatter(
                $item['id'], $item['moyen'], $this->subMatter, 
                $this->cutting, $this->getCoeff(), $item['rang']
            );
        }

        // Déclenchement d'Evenement
        FrenshMoyenneEvent::dispatch(
            $this->classe->id,
            $this->matter,
            $this->cutting
        );
    }

    
    private function getCoeff(): int
    {
        return in_array($this->classe->level_id, [3, 4])
        && $this->subMatter == 1
        ? 2
        : 1;
    }
}
