<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

class MoyenneEditEvent
{
    use Dispatchable;

    public $students, $moyens, $str, $user;

    public function __construct($students, $moyens, $str, $user)
    {
        $this->students = $students;
        $this->moyens = $moyens;
        $this->str = $str;
        $this->user = $user;
    }
}
