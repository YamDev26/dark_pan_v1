<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MoyenneBilanMatterEvent
{
    use Dispatchable;

    public $data, $bilan, $cutting, $classe, $user;
    public function __construct($data, $bilan, $cutting, $classe, $user)
    {
        $this->data = $data;
        $this->bilan = $bilan;
        $this->cutting = $cutting;
        $this->classe = $classe;
        $this->user = $user;
    }
}
