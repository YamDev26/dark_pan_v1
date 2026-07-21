<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoyenneAnnuelleMatterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $matter, $cutting, $classe;

    public function __construct($matter, $cutting, $classe)
    {
        $this->matter = $matter;
        $this->cutting = $cutting;
        $this->classe = $classe;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
