<?php

namespace App\Events;


use Illuminate\Foundation\Events\Dispatchable;

class MoyenneImportGlobalEvent
{
    use Dispatchable;

    public $data, $classe, $cutting;
    public function __construct($data, $classe, $cutting)
    {
        $this->data = $data;
        $this->classe = $classe;
        $this->cutting = $cutting;
    }
}
