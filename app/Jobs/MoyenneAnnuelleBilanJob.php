<?php

namespace App\Jobs;

use App\Services\MoyenneAnnuelService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoyenneAnnuelleBilanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $bialan, $cutting, $classe;

    public function __construct($bialan, $cutting, $classe)
    {
        $this->bialan = $bialan;
        $this->cutting = $cutting;
        $this->classe = $classe;
    }

    
    public function handle(): void
    {
        $service = app(MoyenneAnnuelService::class);
        $service->storeMoyenneBilan(
            $this->classe, $this->bialan, $this->cutting
        );

    }
}
