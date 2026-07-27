<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MoyenneImportFrenshJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data, $matter, $cutting, $classe, $user;
    public function __construct($data, $matter, $cutting, $classe, $user)
    {
        $this->data = $data;
        $this->matter = $matter;
        $this->cutting = $cutting;
        $this->classe = $classe;
        $this->user = $user;
    }


    public function handle(): void
    {
        foreach($this->data as $index => $item) {
            MoyenneSubMatterJob::dispatch(
                $item,
                $this->matter,
                $this->cutting,
                $index+1,
                $this->classe,
                $this->user
            );
        }
    }
}
