<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class FrenshMoyenneEvent
{
    use Dispatchable;

    public $classe, $matter, $cutting, $user;
    public function __construct($classe, $matter, $cutting, $user)
    {
        $this->classe = $classe;
        $this->matter = $matter;
        $this->cutting = $cutting;
        $this->user = $user;
    }
}
