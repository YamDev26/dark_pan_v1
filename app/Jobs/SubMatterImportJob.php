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

    protected $data, $subMatter, $cutting, $classe;
    public function __construct($data, $subMatter, $cutting, $classe)
    {
        $this->data = $data;
        $this->subMatter = $subMatter;
        $this->cutting = $cutting;
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
