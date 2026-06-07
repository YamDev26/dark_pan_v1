<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MoyenneEditFrenshEvent
{
    use Dispatchable;

    public $students, $moyen1, $moyen2, $moyen3, $str;

    public function __construct($students, $moyen1, $moyen2, $moyen3, $str)
    {
        $this->students = $students;
        $this->moyen1 = $moyen1;
        $this->moyen2 = $moyen2;
        $this->moyen3 = $moyen3;
        $this->str = $str;
    }
}
