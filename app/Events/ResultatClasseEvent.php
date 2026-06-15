<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ResultatClasseEvent
{
    use Dispatchable;

    public $classe, $cutting;

    public function __construct($classe, $cutting)
    {
        $this->classe = $classe;
        $this->cutting = $cutting;
    }
}
