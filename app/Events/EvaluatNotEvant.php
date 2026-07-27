<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class EvaluatNotEvant
{
    use Dispatchable;

    public $data, $note, $str, $user;
    public function __construct($data, $note, $str, $user)
    {
        $this->data = $data;
        $this->note = $note;
        $this->str = $str;
        $this->user = $user;
    }
}
