<?php

namespace App\Events;


use Illuminate\Foundation\Events\Dispatchable;

class MoyenneImportGlobalEvent
{
    use Dispatchable;

    public $data, $classe, $cutting, $user;
    public function __construct($data, $classe, $cutting, $user)
    {
        $this->data = $data;
        $this->classe = $classe;
        $this->cutting = $cutting;
        $this->user = $user;
    }
}
