<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MoyenneTrimestreEvent
{
    use Dispatchable;

    public $data, $cutting, $classe, $user;
    public function __construct($data, $cutting, $classe, $user)
    {
        $this->data = $data;
        $this->cutting = $cutting;
        $this->classe = $classe;
        $this->user = $user;
    }

}
