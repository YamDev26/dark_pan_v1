<?php

namespace App\Jobs;

use App\Services\MoyenneAnnuelService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoyenneAnnuelleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $cutting, $classe;

    public function __construct($cutting, $classe)
    {
        $this->cutting = $cutting;
        $this->classe = $classe;
    }

    
    public function handle(): void
    {
        $service = app(MoyenneAnnuelService::class);
        $service->storeMoyenne(
            $this->classe, $this->cutting
        );

    }
}
