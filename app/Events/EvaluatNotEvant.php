<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class EvaluatNotEvant
{
    use Dispatchable;

    public $data, $note, $str;
    public function __construct($data, $note, $str)
    {
        $this->data = $data;
        $this->note = $note;
        $this->str = $str;
    }
}
