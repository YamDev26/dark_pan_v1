<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
class MoyenneMatterStoreEvent
{
    use Dispatchable;

    public $students, $moyens, $str;

    public function __construct($students, $moyens, $str)
    {
        $this->students = $students;
        $this->moyens = $moyens;
        $this->str = $str;
    }

}
