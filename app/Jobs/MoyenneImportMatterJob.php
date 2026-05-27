<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoyenneImportMatterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    private $data, $matter, $cutting;
    public function __construct($data, $matter, $cutting)
    {
        $this->data = $data;
        $this->matter = $matter;
        $this->cutting = $cutting;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dts = ClassementStudent($this->data);

        dd($dts);
    }
}
