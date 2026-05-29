<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MoyenneBilanMatterEvent
{
    use Dispatchable;

    public $data, $bilan, $cutting;
    public function __construct($data, $bilan, $cutting)
    {
        $this->data = $data;
        $this->bilan = $bilan;
        $this->cutting = $cutting;
    }
}
