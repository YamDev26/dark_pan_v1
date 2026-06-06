<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class FrenshMoyenneEvent
{
    use Dispatchable;

    public $classe, $matter, $cutting;
    public function __construct($classe, $matter, $cutting)
    {
        $this->classe = $classe;
        $this->matter = $matter;
        $this->cutting = $cutting;
    }
}
