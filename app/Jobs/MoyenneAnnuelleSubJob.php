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

    private $subMatter, $cutting, $classe;

    public function __construct($subMatter, $cutting, $classe)
    {
        $this->subMatter = $subMatter;
        $this->cutting = $cutting;
        $this->classe = $classe;
    }

    
    public function handle(): void
    {
        $service = app(MoyenneAnnuelService::class);
        $service->storeMoyenneSub(
            $this->classe, $this->subMatter, $this->cutting
        );

    }
}
