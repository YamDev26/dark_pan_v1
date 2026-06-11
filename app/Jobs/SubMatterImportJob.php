<?php

namespace App\Jobs;

use App\Services\MoyenneService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubMatterImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data, $cutting, $subMatter, $classe;
    public function __construct($data, $cutting, $subMatter, $classe)
    {
        $this->data = $data;
        $this->cutting = $cutting;
        $this->subMatter = $subMatter;
        $this->classe = $classe;
    }

    
    public function handle(): void
    {
        $service = app(MoyenneService::class);
        $matter = $service->frenshIdGet(
            $this->classe->level_id
        );

        MoyenneSubMatterJob::dispatch(
            $this->data, 
            $matter, 
            $this->cutting, 
            $this->subMatter, 
            $this->classe
        );
    }
}
