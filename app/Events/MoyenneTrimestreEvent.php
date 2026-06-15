<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MoyenneTrimestreEvent
{
    use Dispatchable;

    public $data, $cutting, $classe;
    public function __construct($data, $cutting, $classe)
    {
        $this->data = $data;
        $this->cutting = $cutting;
        $this->classe = $classe;
    }

}
