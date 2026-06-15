<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MoyenneBilanMatterEvent
{
    use Dispatchable;

    public $data, $bilan, $cutting, $classe;
    public function __construct($data, $bilan, $cutting, $classe)
    {
        $this->data = $data;
        $this->bilan = $bilan;
        $this->cutting = $cutting;
        $this->classe = $classe;
    }
}
