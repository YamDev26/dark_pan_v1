<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ResultatClasseEvent
{
    use Dispatchable;

    public $classe, $cutting, $user;

    public function __construct($classe, $cutting, $user)
    {
        $this->classe = $classe;
        $this->cutting = $cutting;
        $this->user = $user;
    }
}
