<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MoyenneEditDrivingEvent
{
    use Dispatchable;

    public $students, $moyens, $absJust, $absNons, $str;
    
    public function __construct($students, $moyens, $absJust, $absNons, $str)
    {
        $this->students = $students;
        $this->moyens = $moyens;
        $this->absJust = $absJust;
        $this->absNons = $absNons;
        $this->str = $str;
    }
}
