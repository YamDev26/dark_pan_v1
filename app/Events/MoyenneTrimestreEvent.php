<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MoyenneTrimestreEvent
{
    use Dispatchable;

    public $data, $cutting;
    public function __construct($data, $cutting)
    {
        $this->data = $data;
        $this->cutting = $cutting;
    }

}
